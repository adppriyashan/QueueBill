@extends('layouts.app')

@section('title', 'Service Schedule: ' . $service->name . ' - QueueBill')
@section('page_title', 'Billing Schedule: ' . $service->name)

@section('content')
    <div class="row g-4">
        <!-- Left Column: Contract Details Card -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Contract Schedule Parameters</h5>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-3 p-3 bg-primary-soft text-primary me-3">
                            <i class="fas fa-file-contract fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-0">{{ $service->name }}</h4>
                            <span class="text-secondary fs-7">Buyer: <strong><a
                                        href="{{ route('companies.show', $service->company) }}"
                                        class="text-decoration-none text-primary">{{ $service->company->name }}</a></strong></span>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="row g-3 mb-3 fs-7">
                            <div class="col-6">
                                <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Start Date</label>
                                <span class="fw-bold text-dark"><i
                                        class="far fa-calendar-alt text-muted me-2"></i>{{ $service->from_date->format('M d, Y') }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Expiration Date</label>
                                <span class="fw-bold text-dark"><i
                                        class="far fa-calendar-times text-muted me-2"></i>{{ $service->to_date->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="row g-3 mb-3 fs-7">
                            <div class="col-6">
                                <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Recurring Cadence</label>
                                <span
                                    class="badge bg-secondary-soft text-secondary text-capitalize font-weight-bold fs-7 py-2 px-3 mt-1"><i
                                        class="fas fa-redo fa-spin-hover me-1"></i>{{ $service->recurring_cadence }}</span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Base Price</label>
                                <span
                                    class="fw-extrabold text-primary fs-5 fw-bold">@currency($service->base_cost)</span>
                            </div>
                        </div>

                        <div class="mb-3 fs-7">
                            <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Invoice Layout
                                Template</label>
                            <span class="fw-semibold text-dark"><i
                                    class="fas fa-file-invoice text-muted me-2"></i>{{ $service->invoiceStructureTemplate->title }}</span>
                            <a href="{{ route('templates.preview', $service->invoiceStructureTemplate->slug) }}"
                                target="_blank" class="fs-8 text-primary ms-2"><i class="fas fa-external-link-alt"></i>
                                Preview</a>
                        </div>

                        <div class="mb-3 fs-7">
                            <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Google Drive Destination
                                Path</label>
                            @if($service->google_drive_path)
                                <code
                                    class="fs-7 bg-light text-success px-2 py-1 rounded d-inline-block mt-1"><i class="fab fa-google-drive me-2"></i>{{ $service->google_drive_path }}</code>
                            @else
                                <span class="text-muted font-italic fs-8"><i class="fas fa-minus me-1"></i>No drive path
                                    registered</span>
                            @endif
                        </div>

                        <div class="mb-3 fs-7">
                            <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Calculated
                                next_billing_date</label>
                            <span class="badge bg-warning-soft text-warning fw-bold fs-7 py-2 px-3 mt-1"><i
                                    class="far fa-clock me-1"></i>{{ $service->next_billing_date->format('M d, Y') }}</span>
                        </div>

                        <div class="mb-3 fs-7">
                            <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Contract Status</label>
                            @if($service->status === 'active')
                                <span class="badge bg-success-soft text-success fw-bold fs-7 py-1 px-2"><i
                                        class="fas fa-circle fs-8 me-1"></i>Active</span>
                            @else
                                <span class="badge bg-danger-soft text-danger fw-bold fs-7 py-1 px-2"><i
                                        class="fas fa-circle fs-8 me-1"></i>Inactive</span>
                            @endif
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <label class="text-muted fs-8 text-uppercase fw-semibold d-block mb-2">Scope Text Block (Parsed
                            Line-by-Line)</label>
                        <div class="bg-light p-3 rounded-3 border">
                            <ul class="mb-0 ps-3 fs-7 text-secondary">
                                @foreach(explode("\n", $service->invoice_includes) as $item)
                                    @if(trim($item))
                                        <li class="mb-1">{{ trim($item) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light border-top p-3 d-flex flex-column gap-2">
                    <a href="{{ route('services.preview-next-invoice', $service) }}" class="btn btn-primary btn-sm w-100 py-2 fw-semibold"><i
                            class="fas fa-file-invoice-dollar me-2"></i>Preview Next Statement</a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-warning btn-sm flex-grow-1"><i
                                class="fas fa-edit me-1"></i>Edit Parameters</a>
                        <form action="{{ route('services.destroy', $service) }}" method="POST" class="flex-grow-1"
                            onsubmit="return confirm('Are you sure you want to remove this contract schedule?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100"><i
                                    class="fas fa-trash-alt me-1"></i>Delete Contract</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Pre-emptive Manual Adjustments & Audit Invoices -->
        <div class="col-12 col-lg-7">

            <!-- Module 4: Dynamic Ad-Hoc Adjustments Engine (Line Injections) - Scenario A (Pre-emptive) -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-1">Scenario A: Pre-emptive Manual Adjustments</h5>
                    <p class="text-secondary mb-0 fs-7">Register manual adjustments prior to invoicing. Intercepted and
                        injected in the next billing cycle run.</p>
                </div>

                <div class="card-body p-4">
                    <!-- Add New Pending Line Form -->
                    <form method="POST" action="{{ route('services.adjustments.store', $service) }}"
                        class="p-3 bg-light rounded-4 border mb-4">
                        @csrf
                        <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-plus-circle text-primary me-2"></i>Inject
                            Pending Ad-Hoc Line</h6>

                        <div class="row g-3">
                            <div class="col-12 col-md-7">
                                <label for="description" class="form-label fs-8 text-secondary fw-semibold">Adjustment
                                    Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="description" name="description"
                                    required placeholder="e.g. Migration Setup Credit or Server Overage Fee">
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Amount (@currencySymbol) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="amount"
                                    name="amount" required placeholder="0.00">
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100 py-2"><i class="fas fa-plus"></i>
                                    Inject</button>
                            </div>
                        </div>
                        <div class="form-text text-muted fs-8 mt-2">Specify positive numbers for fees or negative numbers
                            for credits/discounts.</div>
                    </form>

                    <!-- List of Injected Pending Lines -->
                    <h6 class="fw-bold text-dark mb-3">Pending Injection Queue for Next Cycle
                        ({{ $service->next_billing_date->format('M d, Y') }})</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle fs-7">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th style="width: 55%">Adjustment Description</th>
                                    <th style="width: 25%">Amount</th>
                                    <th style="width: 20%" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($service->pendingInvoiceLines as $line)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $line->description }}</div>
                                            <div class="text-muted fs-8">Injected by {{ $line->creator?->name ?? 'System' }} on
                                                {{ $line->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td>
                                            @if($line->amount < 0)
                                                <span class="text-success fw-bold">-@currency(abs($line->amount))
                                                    (Credit)</span>
                                            @else
                                                <span class="text-danger fw-bold">+@currency($line->amount)
                                                    (Overage)</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('services.adjustments.destroy', $line) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to remove this injected line?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm border-0"
                                                    title="Delete pending line">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">
                                            No pending manual ad-hoc lines scheduled. Next invoice will only contain base
                                            pricing contract.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section: Invoice Billing History -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="fw-bold mb-0">Generated Cycle Invoices</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-premium mb-0 align-middle fs-7">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Billing Period</th>
                                    <th>Total</th>
                                    <th>Version</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($service->invoices as $invoice)
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="{{ route('invoices.show', $invoice) }}"
                                                    class="fw-bold text-dark text-decoration-none hover-primary">{{ $invoice->invoice_number }}</a>
                                                <span class="text-muted d-block fs-8">Issued
                                                    {{ $invoice->issue_date->format('M d, Y') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{ $invoice->period_from->format('M d') }} -
                                                {{ $invoice->period_to->format('M d, Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">@currency($invoice->total)</span>
                                        </td>
                                        <td>
                                            @if($invoice->version > 1)
                                                <span class="badge bg-warning text-dark font-weight-bold">v{{ $invoice->version }}
                                                    (Revised)</span>
                                            @else
                                                <span class="badge bg-light text-dark border">v1</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('invoices.show', $invoice) }}"
                                                class="btn btn-light btn-sm border"><i class="far fa-eye me-1"></i>View /
                                                Revise</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No invoices generated for this subscription schedule yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Section: Recent Delivery Logs -->
            @php
                $serviceEmailLogs = \App\Models\EmailLog::whereIn('invoice_id', $service->invoices->pluck('id'))->latest()->take(5)->get();
            @endphp
            @if($serviceEmailLogs->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Outgoing Document Emails</h5>
                        <a href="{{ route('logs.index') }}" class="btn btn-sm btn-light border fs-8"><i class="fas fa-external-link-alt me-1"></i>All Logs</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-premium mb-0 align-middle fs-7">
                            <thead>
                                <tr>
                                    <th>Subject Title</th>
                                    <th>Date / Time</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceEmailLogs as $log)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-primary d-block">{{ $log->subject }}</span>
                                            <small class="text-muted d-block text-truncate fs-9" style="max-width: 200px;" title="{{ $log->body }}">{{ $log->body }}</small>
                                        </td>
                                        <td>
                                            <span class="text-secondary fs-8">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-soft text-success"><i class="fas fa-check me-1"></i>{{ strtoupper($log->status) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('invoices.show', $log->invoice_id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 fs-8 rounded-3" target="_blank" title="View Document">
                                                <i class="fas fa-external-link-alt me-1"></i>View Doc
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection