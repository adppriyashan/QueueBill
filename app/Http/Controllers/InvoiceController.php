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

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['company', 'recurringService'])->latest()->paginate(15);
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

        // 4. Log simulated email dispatch (Revised Statement)
        $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email ?? 'billing@queuebill.com';
        $creator = auth()->user() ?? \App\Models\User::first();
        $senderCompany = $creator ? $creator->company_name : 'QueueBill Automation System';

        $bodyText = "Dear {$invoice->company->name},\n\nThank you for doing business with us! We truly appreciate your continued partnership.\n\n" .
                    "Please find attached the Revised Statement (version {$newVersion}) for Invoice {$invoice->invoice_number}.\n\n" .
                    "Reason for Revision: Added retroactive item: {$validated['description']} (" . currency_symbol() . number_format($validated['amount'], 2) . ").\n" .
                    "New Total Due: " . currency_symbol() . number_format($newTotal, 2) . "\n\nWarm Regards,\n{$senderCompany}.";

        EmailLog::create([
            'invoice_id' => $invoice->id,
            'sender' => $senderEmail,
            'recipient' => $invoice->company->email,
            'subject' => "REVISED STATEMENT (v{$newVersion}) for Invoice {$invoice->invoice_number}",
            'body' => $bodyText,
            'status' => 'sent'
        ]);

        // 5. Log Google Drive Upload Simulation to the registered path
        $drivePath = $invoice->google_drive_path ?? '/QueueBill/Revisions';
        GoogleDriveLog::create([
            'invoice_id' => $invoice->id,
            'google_drive_path' => $drivePath,
            'version' => $newVersion,
            'status' => 'success',
        ]);

        // Update upload timestamp on invoice
        $invoice->update([
            'uploaded_to_drive_at' => now()
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', "Invoice {$invoice->invoice_number} successfully revised to v{$newVersion}. Simulated email dispatched and revised document uploaded to Google Drive path: {$drivePath}");
    }
}
