<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringService;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\EmailLog;
use App\Models\GoogleDriveLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InvoiceStatementMail;
use App\Services\GoogleDriveService;

class ProcessAutomatedInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queuebill:process-invoices {--date= : Custom simulation date (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Midnight Cron simulation: process active recurring service billing contracts, inject ad-hoc lines, email audits, and sync Google Drive.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $simulationDateStr = $this->option('date');
        $today = $simulationDateStr ? Carbon::parse($simulationDateStr) : Carbon::today();

        $this->info("Starting QueueBill cycle billing engine run for simulated date: " . $today->format('Y-m-d'));

        // Query active services where next_billing_date <= today AND next_billing_date <= to_date
        $services = RecurringService::where('status', 'active')
            ->where('next_billing_date', '<=', $today->copy()->endOfDay()->toDateTimeString())
            ->where('next_billing_date', '<=', DB::raw('to_date'))
            ->with(['company', 'invoiceStructureTemplate', 'pendingInvoiceLines', 'creator'])
            ->get();

        if ($services->isEmpty()) {
            $this->comment("No active subscription billing schedules due for processing.");
            return 0;
        }

        $processedCount = 0;

        foreach ($services as $service) {
            $this->info("Processing Service: ID {$service->id} - {$service->name} for Buyer: {$service->company->name}");

            // 1. Calculate Period Boundaries
            $periodFrom = Carbon::parse($service->next_billing_date);
            $periodTo = clone $periodFrom;

            if ($service->recurring_cadence === 'month') {
                $periodTo->addMonth()->subDay();
            } elseif ($service->recurring_cadence === '6_months') {
                $periodTo->addMonths(6)->subDay();
            } elseif ($service->recurring_cadence === 'year') {
                $periodTo->addYear()->subDay();
            }

            // Cap period_to at overall contract to_date if needed
            if ($periodTo->gt($service->to_date)) {
                $periodTo = Carbon::parse($service->to_date);
            }

            // 2. Generate Unique Invoice Number: QB-YYYYMMDD-{ServiceID}
            $invoiceNumber = "QB-" . $today->format('Ymd') . "-" . str_pad($service->id, 4, '0', STR_PAD_LEFT);

            // Double check if invoice already exists to avoid duplication
            $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();
            if ($exists) {
                $this->warn("Invoice {$invoiceNumber} already exists for this cycle run. Skipping.");
                continue;
            }

            // 3. Create Invoice Base Profile
            $invoice = Invoice::create([
                'company_id' => $service->company_id,
                'recurring_service_id' => $service->id,
                'invoice_structure_template_id' => $service->invoice_structure_template_id,
                'invoice_number' => $invoiceNumber,
                'version' => 1,
                'issue_date' => $today->format('Y-m-d'),
                'due_date' => $today->copy()->addDays(14)->format('Y-m-d'),
                'period_from' => $periodFrom->format('Y-m-d'),
                'period_to' => $periodTo->format('Y-m-d'),
                'subtotal' => 0.00,
                'total' => 0.00,
                'google_drive_path' => $service->google_drive_path ?? '/QueueBill/Invoices'
            ]);

            // 4. Inject standard base price item
            $invoice->invoiceItems()->create([
                'description' => "{$service->name} (Base Subscription)",
                'amount' => $service->base_cost,
                'is_adhoc' => false
            ]);

            // 5. Parse invoice_includes block line-by-line into individual item descriptions with $0
            $includesLines = explode("\n", $service->invoice_includes);
            foreach ($includesLines as $line) {
                $trimmedLine = trim($line);
                if (!empty($trimmedLine)) {
                    $invoice->invoiceItems()->create([
                        'description' => $trimmedLine,
                        'amount' => 0.00,
                        'is_adhoc' => false
                    ]);
                }
            }

            // 6. Scenario A Pre-emptive adjustments interception
            $pendingAdjustments = $service->pendingInvoiceLines()->where('billing_status', 'pending')->get();
            foreach ($pendingAdjustments as $adj) {
                $invoice->invoiceItems()->create([
                    'description' => $adj->description . " (Ad-hoc Adjustment)",
                    'amount' => $adj->amount,
                    'is_adhoc' => true
                ]);

                // Flag the pending line as Invoiced
                $adj->update(['billing_status' => 'invoiced']);
                $this->info("  -> Intercepted & injected pre-emptive adjustment: '{$adj->description}' ({$adj->amount})");
            }

            // 7. Rollup Subtotals and Totals
            $subtotal = $invoice->invoiceItems()->sum('amount');
            $invoice->update([
                'subtotal' => $subtotal,
                'total' => $subtotal
            ]);

            // Load relations for PDF rendering context
            $invoice->load(['company', 'recurringService.invoiceStructureTemplate', 'invoiceItems', 'creator']);

            // Generate PDF statement using the clean, dedicated PDF view
            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
            $pdfData = $pdf->output();
            $pdfFilename = "{$invoiceNumber}_v1.pdf";

            // 8. Real Email Broadcast dispatch
            $creator = $service->creator;
            $currency = $creator->currency ?? '$';
            $senderEmail = $service->invoiceStructureTemplate->sender_email ?? 'billing@queuebill.com';
            $subjectText = "New Statement Generated: {$invoiceNumber} - QueueBill";
            $bodyText = "Dear {$service->company->name},\n\nThank you for doing business with us! We truly appreciate your continued partnership.\n\nYour new statement {$invoiceNumber} has been generated for period {$periodFrom->format('M d, Y')} to {$periodTo->format('M d, Y')}.\n\nPlease find your invoice document attached to this email.\n\nTotal Due: " . $currency . number_format($subtotal, 2) . "\n\nWarm Regards,\n" . ($creator->company_name ?? env('APP_NAME', 'QueueBill')) . ".";

            $emailStatus = 'failed';
            try {
                Mail::to($service->company->email)->send(new InvoiceStatementMail($subjectText, $bodyText, $pdfData, $pdfFilename));
                $emailStatus = 'sent';
                $this->info("  -> Real email statement successfully sent to {$service->company->email}");
            } catch (\Exception $e) {
                $this->error("  -> Failed to send real email: " . $e->getMessage());
            }

            EmailLog::create([
                'invoice_id' => $invoice->id,
                'sender' => $senderEmail,
                'recipient' => $service->company->email,
                'subject' => $subjectText,
                'body' => $bodyText,
                'status' => $emailStatus
            ]);

            // 9. Real Google Drive upload action
            $drivePath = $service->google_drive_path ?? '/QueueBill/Invoices';
            $driveStatus = 'failed';
            $driveDetails = "Simulated or no integration connected.";

            if ($creator && $creator->google_access_token) {
                try {
                    $driveService = resolve(GoogleDriveService::class);
                    $fileId = $driveService->uploadFile($creator, $pdfFilename, $pdfData, $drivePath);
                    $driveStatus = 'success';
                    $driveDetails = "Invoice v1 successfully uploaded and saved to Google Drive (ID: {$fileId}) at: '{$drivePath}'.";
                    $this->info("  -> Real Google Drive upload successful!");
                } catch (\Exception $e) {
                    $driveDetails = "Failed to upload to Google Drive: " . $e->getMessage();
                    $this->error("  -> Google Drive upload failed: " . $e->getMessage());
                }
            } else {
                $driveDetails = "Google Drive not connected for the subscription creator. Connect Google Drive in Settings to enable automated backup.";
                $this->warn("  -> Google Drive not connected for user " . ($creator->name ?? 'Unknown') . ". Sync skipped.");
            }

            GoogleDriveLog::create([
                'invoice_id' => $invoice->id,
                'google_drive_path' => $drivePath,
                'file_name' => $pdfFilename,
                'status' => $driveStatus,
                'details' => $driveDetails
            ]);

            if ($driveStatus === 'success') {
                // Save upload stamp on invoice
                $invoice->update([
                    'uploaded_to_drive_at' => now()
                ]);
            }

            // 10. Advance next_billing_date cycle window
            $nextBillingDate = clone $periodFrom;
            if ($service->recurring_cadence === 'month') {
                $nextBillingDate->addMonth();
            } elseif ($service->recurring_cadence === '6_months') {
                $nextBillingDate->addMonths(6);
            } elseif ($service->recurring_cadence === 'year') {
                $nextBillingDate->addYear();
            }

            // Check if contract has completed overall expiration
            if ($nextBillingDate->gt($service->to_date)) {
                $service->update([
                    'status' => 'inactive',
                    'next_billing_date' => $nextBillingDate->format('Y-m-d')
                ]);
                $this->info("  -> Advanced next billing window: Exceeded expiration limit ({$service->to_date->format('Y-m-d')}). Contract suspended/completed.");
            } else {
                $service->update([
                    'next_billing_date' => $nextBillingDate->format('Y-m-d')
                ]);
                $this->info("  -> Advanced next billing cycle window to: " . $nextBillingDate->format('Y-m-d'));
            }

            $processedCount++;
        }

        $this->info("Successfully processed {$processedCount} recurring billing schedules!");
        return 0;
    }
}
