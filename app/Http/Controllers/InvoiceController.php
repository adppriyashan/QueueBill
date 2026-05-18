<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\RecurringService;
use App\Models\PendingInvoiceLine;
use App\Models\EmailLog;
use App\Models\GoogleDriveLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use App\Mail\InvoiceStatementMail;
use App\Services\GoogleDriveService;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['company', 'recurringService'])->latest()->paginate(10);
        $totalInvoices = Invoice::count();
        $totalBilled = Invoice::sum('total');
        $averageInvoice = Invoice::avg('total') ?? 0;

        return view('invoices.index', compact('invoices', 'totalInvoices', 'totalBilled', 'averageInvoice'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['company', 'recurringService.invoiceStructureTemplate', 'invoiceItems']);
        return view('invoices.show', compact('invoice'));
    }

    // Module 4 - Scenario A: Pre-emptive Manual Adjustments (Create)
    public function storeAdjustment(Request $request, RecurringService $service)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
        ]);

        $service->pendingInvoiceLines()->create([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'billing_status' => 'pending',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('services.show', $service)
            ->with('success', 'Manual pre-emptive ad-hoc line injected successfully into cycle queue!');
    }

    // Module 4 - Scenario A: Pre-emptive Manual Adjustments (Delete)
    public function destroyAdjustment(PendingInvoiceLine $line)
    {
        $service_id = $line->recurring_service_id;
        $line->delete();

        return redirect()->route('services.show', $service_id)
            ->with('success', 'Pending ad-hoc line removed from cycle queue successfully.');
    }

    // Module 4 - Scenario B: Retroactive post-billing adjustments & revised statement compile
    public function revise(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric'],
        ]);

        // 1. Increment Version Indicator
        $oldVersion = $invoice->version;
        $newVersion = $oldVersion + 1;

        // 2. Add manual retroactive ad-hoc items
        $invoice->invoiceItems()->create([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'is_adhoc' => true
        ]);

        // 3. Recalculate totals
        $newSubtotal = $invoice->invoiceItems()->sum('amount');
        $newTotal = $newSubtotal; // Assuming no tax/discounts for simplicity

        $invoice->update([
            'version' => $newVersion,
            'subtotal' => $newSubtotal,
            'total' => $newTotal
        ]);

        // 4. Real Email Dispatch (Revised Statement)
        $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email ?? 'billing@queuebill.com';
        $creator = auth()->user() ?? User::first();
        $senderCompany = $creator ? $creator->company_name : env('APP_NAME', 'QueueBill');

        $bodyText = "Dear {$invoice->company->name},\n\nThank you for doing business with us! We truly appreciate your continued partnership.\n\n" .
                    "Please find attached the Revised Statement (version {$newVersion}) for Invoice {$invoice->invoice_number}.\n\n" .
                    "Reason for Revision: Added retroactive item: {$validated['description']} (" . currency_symbol() . number_format($validated['amount'], 2) . ").\n" .
                    "New Total Due: " . currency_symbol() . number_format($newTotal, 2) . "\n\nWarm Regards,\n{$senderCompany}.";

        // Load relations for PDF rendering context
        $invoice->load(['company', 'recurringService.invoiceStructureTemplate', 'invoiceItems', 'creator']);

        // Generate PDF using the clean, dedicated PDF view
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdfData = $pdf->output();
        $pdfFilename = "{$invoice->invoice_number}_v{$newVersion}.pdf";

        $emailStatus = 'failed';
        $subjectText = "REVISED STATEMENT (v{$newVersion}) for Invoice {$invoice->invoice_number}";
        try {
            Mail::to($invoice->company->email)->send(new InvoiceStatementMail($subjectText, $bodyText, $pdfData, $pdfFilename));
            $emailStatus = 'sent';
        } catch (\Exception $e) {
            // failed, status logged below
        }

        EmailLog::create([
            'invoice_id' => $invoice->id,
            'sender' => $senderEmail,
            'recipient' => $invoice->company->email,
            'subject' => $subjectText,
            'body' => $bodyText,
            'status' => $emailStatus
        ]);

        // 5. Real Google Drive Upload Action
        $drivePath = $invoice->google_drive_path ?? '/QueueBill/Revisions';
        $driveStatus = 'failed';
        $driveDetails = "Simulated or no integration connected.";

        if ($creator && $creator->google_access_token) {
            try {
                $driveService = resolve(GoogleDriveService::class);
                $fileId = $driveService->uploadFile($creator, $pdfFilename, $pdfData, $drivePath);
                $driveStatus = 'success';
                $driveDetails = "Invoice revision v{$newVersion} successfully uploaded and saved to Google Drive (ID: {$fileId}) at: '{$drivePath}'.";
            } catch (\Exception $e) {
                $driveDetails = "Failed to upload revision to Google Drive: " . $e->getMessage();
            }
        } else {
            $driveDetails = "Google Drive not connected for the subscription creator. Connect Google Drive in Settings to enable automated backup.";
        }

        GoogleDriveLog::create([
            'invoice_id' => $invoice->id,
            'google_drive_path' => $drivePath,
            'file_name' => $pdfFilename,
            'status' => $driveStatus,
            'details' => $driveDetails
        ]);

        if ($driveStatus === 'success') {
            // Update upload timestamp on invoice
            $invoice->update([
                'uploaded_to_drive_at' => now()
            ]);
        }

        $successMsg = "Invoice {$invoice->invoice_number} successfully revised to v{$newVersion}.";
        if ($emailStatus === 'sent') {
            $successMsg .= " Revised email statement successfully dispatched.";
        } else {
            $successMsg .= " Email dispatch failed (check logs).";
        }
        if ($driveStatus === 'success') {
            $successMsg .= " Document saved securely to Google Drive path: {$drivePath}.";
        } else {
            $successMsg .= " Drive backup skipped/failed (check logs/settings).";
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', $successMsg);
    }

    public function resendEmail(Invoice $invoice)
    {
        // 1. Gather context
        $creator = $invoice->creator;
        $senderCompany = $creator->company_name ?? env('APP_NAME', 'QueueBill');
        $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email ?? 'billing@queuebill.com';
        $currency = $creator->currency ?? '$';

        $subjectText = "STATEMENT DISPATCH: Invoice {$invoice->invoice_number}";
        $bodyText = "Dear {$invoice->company->name},\n\n" .
                    "Please find attached your Statement for Invoice {$invoice->invoice_number}.\n\n" .
                    "Total Amount Due: " . $currency . number_format($invoice->total, 2) . "\n\n" .
                    "Warm Regards,\n{$senderCompany}.";

        // Load relations for PDF rendering context
        $invoice->load(['company', 'recurringService.invoiceStructureTemplate', 'invoiceItems', 'creator']);

        // Generate PDF using the clean, dedicated PDF view
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdfData = $pdf->output();
        $pdfFilename = "{$invoice->invoice_number}_v{$invoice->version}.pdf";

        $emailStatus = 'failed';
        try {
            Mail::to($invoice->company->email)->send(new InvoiceStatementMail($subjectText, $bodyText, $pdfData, $pdfFilename));
            $emailStatus = 'sent';
        } catch (\Exception $e) {
            // failed, status logged below
        }

        EmailLog::create([
            'invoice_id' => $invoice->id,
            'sender' => $senderEmail,
            'recipient' => $invoice->company->email,
            'subject' => $subjectText,
            'body' => $bodyText,
            'status' => $emailStatus
        ]);

        if ($emailStatus === 'sent') {
            return redirect()->route('invoices.show', $invoice)
                ->with('success', "Statement email successfully resent to {$invoice->company->email}!");
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('error', "Failed to resend statement email. Please check configuration/logs.");
    }
}
