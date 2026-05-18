<?php

namespace App\Http\Controllers;

use App\Models\RecurringService;
use App\Models\Company;
use App\Models\InvoiceStructureTemplate;
use Illuminate\Http\Request;

class RecurringServiceController extends Controller
{
    public function index()
    {
        $services = RecurringService::with(['company', 'invoiceStructureTemplate'])->latest()->paginate(10);
        return view('services.index', compact('services'));
    }

    public function create(Request $request)
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $templates = InvoiceStructureTemplate::where('status', 'active')->orderBy('title')->get();
        $selected_company_id = $request->query('company_id');

        return view('services.create', compact('companies', 'templates', 'selected_company_id'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'invoice_structure_template_id' => ['required', 'exists:invoice_structure_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'recurring_cadence' => ['required', 'in:month,6_months,year'],
            'base_cost' => ['required', 'numeric', 'min:0'],
            'invoice_includes' => ['required', 'string'],
            'google_drive_path' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Default next_billing_date to from_date on initial creation
        $validated['next_billing_date'] = $validated['from_date'];

        RecurringService::create($validated);

        return redirect()->route('companies.show', $validated['company_id'])
            ->with('success', 'Recurring service cycle scheduled successfully!');
    }

    public function show(RecurringService $service)
    {
        $service->load([
            'company',
            'invoiceStructureTemplate',
            'pendingInvoiceLines.creator',
            'invoices' => function ($query) {
                $query->latest();
            }
        ]);

        return view('services.show', compact('service'));
    }

    public function edit(RecurringService $service)
    {
        $companies = Company::where('status', 'active')->orderBy('name')->get();
        $templates = InvoiceStructureTemplate::where('status', 'active')->orderBy('title')->get();

        return view('services.edit', compact('service', 'companies', 'templates'));
    }

    public function update(Request $request, RecurringService $service)
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'invoice_structure_template_id' => ['required', 'exists:invoice_structure_templates,id'],
            'name' => ['required', 'string', 'max:255'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'recurring_cadence' => ['required', 'in:month,6_months,year'],
            'base_cost' => ['required', 'numeric', 'min:0'],
            'invoice_includes' => ['required', 'string'],
            'google_drive_path' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // If from_date was changed and is now in future compared to next_billing_date, update it
        if ($service->from_date->format('Y-m-d') !== date('Y-m-d', strtotime($validated['from_date']))) {
            $validated['next_billing_date'] = $validated['from_date'];
        }

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Recurring service scheduling updated successfully!');
    }

    public function destroy(RecurringService $service)
    {
        $company_id = $service->company_id;
        $service->delete();

        return redirect()->route('companies.show', $company_id)
            ->with('success', 'Recurring service scheduling removed successfully.');
    }

    public function previewNextInvoice(RecurringService $service)
    {
        $service->load(['company', 'invoiceStructureTemplate', 'pendingInvoiceLines' => function ($query) {
            $query->where('billing_status', 'pending');
        }]);

        $today = \Carbon\Carbon::parse($service->next_billing_date);
        $periodFrom = clone $today;
        $periodTo = clone $periodFrom;

        if ($service->recurring_cadence === 'month') {
            $periodTo->addMonth()->subDay();
        } elseif ($service->recurring_cadence === '6_months') {
            $periodTo->addMonths(6)->subDay();
        } elseif ($service->recurring_cadence === 'year') {
            $periodTo->addYear()->subDay();
        }

        if ($periodTo->gt($service->to_date)) {
            $periodTo = \Carbon\Carbon::parse($service->to_date);
        }

        $invoiceNumber = "QB-PREVIEW-" . str_pad($service->id, 4, '0', STR_PAD_LEFT);

        // Build simulated line items list
        $items = [];

        // 1. Base cost
        $items[] = (object)[
            'description' => "{$service->name} (Base Subscription)",
            'amount' => $service->base_cost,
            'is_adhoc' => false
        ];

        // 2. Includes lines
        $includesLines = explode("\n", $service->invoice_includes);
        foreach ($includesLines as $line) {
            $trimmedLine = trim($line);
            if (!empty($trimmedLine)) {
                $items[] = (object)[
                    'description' => $trimmedLine,
                    'amount' => 0.00,
                    'is_adhoc' => false
                ];
            }
        }

        // 3. Pending adjustments
        foreach ($service->pendingInvoiceLines as $adj) {
            $items[] = (object)[
                'description' => $adj->description . " (Ad-hoc Adjustment)",
                'amount' => $adj->amount,
                'is_adhoc' => true
            ];
        }

        // Roll up totals
        $subtotal = collect($items)->sum('amount');
        $total = $subtotal;

        return view('services.preview_invoice', compact(
            'service',
            'periodFrom',
            'periodTo',
            'invoiceNumber',
            'items',
            'subtotal',
            'total'
        ));
    }
}
