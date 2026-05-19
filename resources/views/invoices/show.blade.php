@extends('layouts.app')

@section('title', 'Invoice Details: ' . $invoice->invoice_number . ' - QueueBill')
@section('page_title', 'Invoice Details: ' . $invoice->invoice_number)

@section('styles')
<style>
    /* Premium Invoice Sheet Card styling */
    .invoice-sheet {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Branded Header bar customized dynamically by Template rules */
    .sheet-header-bar {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        padding: 35px;
    }

    @php
        $templateTitle = $invoice->recurringService?->invoiceStructureTemplate?->title ?? '';
        $isHosting = str_contains(strtolower($templateTitle), 'hosting') || str_contains(strtolower($templateTitle), 'cloud');
        $isCorp = str_contains(strtolower($templateTitle), 'corporate') || str_contains(strtolower($templateTitle), 'enterprise');
    @endphp

    @if($isHosting)
    .sheet-header-bar {
        background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
    }
    .text-theme-price {
        color: #06b6d4 !important;
    }
    @elseif($isCorp)
    .sheet-header-bar {
        background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
    }
    .text-theme-price {
        color: #1e293b !important;
    }
    @else
    .text-theme-price {
        color: #4f46e5 !important;
    }
    @endif

    .sheet-body {
        padding: 35px;
    }

    .table-sheet-items th {
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding: 10px;
    }

    .table-sheet-items td {
        padding: 14px 10px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .sheet-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
        padding: 4px 12px;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.75rem;
        backdrop-filter: blur(8px);
    }

    @media print {
        html, body {
            background: #ffffff !important;
            color: #000000 !important;
            height: auto !important;
            min-height: auto !important;
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: visible !important;
        }
        main {
            padding: 0 !important;
            min-height: auto !important;
            display: block !important;
            overflow: visible !important;
        }
        .container-fluid {
            padding: 0 !important;
        }
        .row {
            margin: 0 !important;
            padding: 0 !important;
        }
        /* Hide everything except the invoice column sheet */
        .sidebar, .navbar, .alert, .btn, .card:not(.invoice-sheet), form, .col-lg-4, footer {
            display: none !important;
        }
        .col-lg-8, .col-12 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .invoice-sheet {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .sheet-header-bar {
            background: #f8fafc !important;
            color: #0f172a !important;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 20px 0 !important;
        }
        .sheet-header-bar h2, .sheet-header-bar h3 {
            color: #0f172a !important;
        }
        .sheet-badge {
            display: none !important;
        }
        .sheet-body {
            padding: 20px 0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="row g-4">
    <!-- Invoice Sheet and Audits Column -->
    <div class="col-12 col-lg-8">
        <div class="invoice-sheet mb-4">
            <!-- Sheet Header -->
            <div class="sheet-header-bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    @if($invoice->creator?->company_logo)
                        <div class="p-2 border rounded bg-white" style="box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                            <img src="{{ asset($invoice->creator->company_logo) }}" alt="Logo" style="height: 50px; max-width: 150px; object-fit: contain;">
                        </div>
                    @endif
                    <div>
                        <h2 class="fw-bold tracking-tight mb-1">{{ $invoice->creator?->company_name ?? env('APP_NAME', 'QueueBill') }}</h2>
                    </div>
                </div>
                <div class="text-md-end">
                    <h3 class="fw-bold mb-0">INVOICE @if($invoice->version > 1) (Revised) @endif</h3>
                    <span class="fs-6 opacity-75">#{{ $invoice->invoice_number }}</span>
                </div>
            </div>

            <!-- Sheet Body -->
            <div class="sheet-body">
                <!-- Meta Rows -->
                <div class="row g-4 mb-5 fs-7">
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed From</h6>
                        <strong class="text-dark d-block">{{ $invoice->creator?->company_name ?? env('APP_NAME', 'QueueBill') }}</strong>
                        <span class="text-muted d-block">{!! nl2br(e($invoice->creator?->company_address ?? "100 Revenue Way, Suite A\nAustin, TX 78701")) !!}</span>
                        
                        <!-- Custom Fallback Email overridden via template properties -->
                        <span class="text-primary fw-medium d-block mt-2">
                            <i class="far fa-envelope me-1"></i>
                            @php
                                $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email;
                            @endphp
                            @if($senderEmail)
                                <strong>{{ $senderEmail }}</strong> <small class="text-muted">(Override)</small>
                            @else
                                hello@queuebill.com <small class="text-muted">(System Default)</small>
                            @endif
                        </span>
                        <br>
                    </div>
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed To</h6>
                        <strong class="text-dark d-block">{{ $invoice->company->name }}</strong>
                        <span class="text-muted d-block">{{ $invoice->company->address ?? '—' }}</span>
                        <span class="text-primary fw-medium d-block mt-2">
                            <i class="far fa-envelope me-1"></i>{{ $invoice->company->email }}
                        </span>
                        <br>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Cycle Timeline</h6>
                        <div class="mb-1"><strong>Issue Date:</strong> <span class="text-muted">{{ $invoice->issue_date->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Due Date:</strong> <span class="text-muted">{{ $invoice->due_date->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Period From:</strong> <span class="text-muted">{{ $invoice->period_from->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Period To:</strong> <span class="text-muted">{{ $invoice->period_to->format('M d, Y') }}</span></div>
                        <br>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="table-responsive mb-5">
                    <table class="table table-sheet-items mb-0 fs-7">
                        <thead>
                            <tr>
                                <th style="width: 60%">Item Description</th>
                                <th style="width: 15%" class="text-center">Qty</th>
                                <th style="width: 25%" class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $baseItem = $invoice->invoiceItems->first(function($item) {
                                    return !$item->is_adhoc && str_contains(strtolower($item->description), 'base subscription');
                                });
                                if (!$baseItem) {
                                    $baseItem = $invoice->invoiceItems->first(function($item) {
                                        return !$item->is_adhoc && $item->amount > 0;
                                    });
                                }
                                
                                $scopeItems = $invoice->invoiceItems->filter(function($item) use ($baseItem) {
                                    return !$item->is_adhoc && $item->amount == 0 && ($baseItem ? $item->id !== $baseItem->id : true);
                                });
                                
                                $otherItems = $invoice->invoiceItems->filter(function($item) use ($baseItem, $scopeItems) {
                                    $excludeIds = [];
                                    if ($baseItem) $excludeIds[] = $baseItem->id;
                                    foreach ($scopeItems as $si) $excludeIds[] = $si->id;
                                    return !in_array($item->id, $excludeIds);
                                });
                            @endphp

                            @if($baseItem)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $baseItem->description }}</div>
                                        @if($scopeItems->isNotEmpty())
                                            <div class="mt-1 text-secondary">
                                                <ul class="ps-3 mb-0 fs-8" style="list-style-type: square;">
                                                    @foreach($scopeItems as $scope)
                                                        <li>{{ $scope->description }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center text-secondary">1</td>
                                    <td class="text-end fw-bold text-dark">
                                        {{ format_currency($baseItem->amount, $invoice->created_by) }}
                                    </td>
                                </tr>
                            @endif

                            @foreach($otherItems as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $item->description }}</div>
                                        @if($item->is_adhoc)
                                            <span class="badge bg-warning-soft text-warning fs-9 px-2 py-0.5 mt-1 d-inline-block"><i class="fas fa-plus-circle me-1"></i>Ad-Hoc Line Injection</span>
                                        @else
                                            <span class="badge bg-light text-secondary fs-9 px-2 py-0.5 mt-1 d-inline-block">Additional contract item</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-secondary">1</td>
                                    <td class="text-end fw-bold text-dark">
                                        @if($item->amount < 0)
                                            <span class="text-success">-{{ format_currency(abs($item->amount), $invoice->created_by) }}</span>
                                        @else
                                            {{ format_currency($item->amount, $invoice->created_by) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals row -->
                <div class="row justify-content-between text-md-end fs-7">
                    <div class="col-12 col-md-6 text-md-start">
                        @if($invoice->recurringService && $invoice->recurringService->note)
                            <div class="card bg-light border-0 mb-4 mt-2">
                                <div class="card-body py-3 px-4">
                                    <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Notes</h6>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $invoice->recurringService->note }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-secondary fw-semibold">Subtotal:</span>
                            <span class="fw-bold text-dark">{{ format_currency($invoice->subtotal, $invoice->created_by) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom fs-6">
                            <span class="text-dark fw-bold">Total Amount Due:</span>
                            <span class="fw-extrabold text-theme-price fs-4 fw-bold">{{ format_currency($invoice->total, $invoice->created_by) }}</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-muted fs-8 font-italic">"Your Recurring Revenue, Perfectly Aligned."</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Activity Logs Auditing -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Delivery & Google Drive Transaction logs</h5>
                <p class="text-secondary mb-0 fs-7">Audits for simulated email dispatch and PDF upload actions generated during revisions.</p>
            </div>
            
            <div class="card-body p-4">
                <!-- Emails log -->
                <h6 class="fw-bold text-dark mb-3"><i class="far fa-envelope-open text-primary me-2"></i>Outgoing Email Broadcast History</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered mb-0 align-middle fs-7">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>Subject</th>
                                <th>To / From</th>
                                <th>Timestamp</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\EmailLog::where('invoice_id', $invoice->id)->latest()->get() as $log)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $log->subject }}</div>
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;" title="{{ $log->body }}">{{ $log->body }}</small>
                                    </td>
                                    <td>
                                        <div class="fs-8">
                                            <strong>To:</strong> {{ $log->recipient }}<br>
                                            <strong>From:</strong> {{ $log->sender }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-8">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-soft text-success"><i class="fas fa-check me-1"></i>{{ strtoupper($log->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        No emails dispatched for this invoice record yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Google Drive logs -->
                <h6 class="fw-bold text-dark mb-3"><i class="fab fa-google-drive text-success me-2"></i>Google Drive Simulated Storage Logs</h6>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 align-middle fs-7">
                        <thead class="table-light text-secondary">
                            <tr>
                                <th>File Name</th>
                                <th>Drive Folder Path</th>
                                <th>Log Details</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\GoogleDriveLog::where('invoice_id', $invoice->id)->latest()->get() as $log)
                                <tr>
                                    <td>
                                        <strong class="text-dark">{{ $log->file_name }}</strong>
                                        <span class="text-muted d-block fs-8">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <code class="text-success">{{ $log->google_drive_path }}</code>
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-8">{{ $log->details }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-soft text-success"><i class="fas fa-check me-1"></i>{{ strtoupper($log->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted">
                                        No Google Drive uploads occurred for this invoice yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Scenario B Retroactive Adjustments Control Form -->
    <div class="col-12 col-lg-4">
        <!-- Action Control Center -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-4 bg-white">
            <h6 class="fw-bold mb-3"><i class="fas fa-tools text-primary me-2"></i>Statement Action Controls</h6>
            <button onclick="window.print()" class="btn btn-primary w-100 py-2.5 fw-semibold fs-7 mb-2 rounded-3 shadow-sm">
                <i class="fas fa-print me-2"></i>Print Statement Sheet
            </button>
            
            <form method="POST" action="{{ route('invoices.resend', $invoice) }}" class="w-100">
                @csrf
                <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold fs-7 mb-2 rounded-3 shadow-sm">
                    <i class="far fa-envelope me-2"></i>Resend Statement Email
                </button>
            </form>

            <a href="{{ route('companies.show', $invoice->company) }}" class="btn btn-light border w-100 py-2 fs-7 rounded-3 text-secondary">
                <i class="fas fa-arrow-left me-2"></i>Return to Profile
            </a>
        </div>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-warning-soft text-warning me-3">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Scenario B Adjustments</h5>
                        <p class="text-secondary mb-0 fs-8">Retroactive Post-Billing Revision</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4 fs-8">
                    <i class="fas fa-exclamation-circle me-1"></i> Adding retroactive manual adjustments here dynamically increments the version indicator (current: <strong>v{{ $invoice->version }}</strong>), recalculates subtotals, compiles a <strong>Revised Statement</strong>, dispatches email logs, and uploads to Google Drive.
                </div>

                <form method="POST" action="{{ route('invoices.revise', $invoice) }}">
                    @csrf

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="retro_description" class="form-label text-secondary fw-semibold fs-8">Adjustment Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('description') is-invalid @enderror" id="retro_description" name="description" required placeholder="e.g. Retroactive Server Usage Adjustment">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div class="mb-4">
                        <label for="retro_amount" class="form-label text-secondary fw-semibold fs-8">Financial Adjustment Amount ({{ currency_symbol($invoice->created_by) }}) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control form-control-sm @error('amount') is-invalid @enderror" id="retro_amount" name="amount" required placeholder="0.00">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted fs-8">Specify negative numbers for credits/reductions.</div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 fw-semibold fs-7"><i class="fas fa-sync-alt me-1"></i>Compile & Resend Revision</button>
                </form>
            </div>
        </div>

        <!-- Google Drive Path Info card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 p-4">
            <h6 class="fw-bold mb-3"><i class="fab fa-google-drive text-success me-2"></i>Google Drive Settings</h6>
            <div class="fs-7 text-secondary mb-3">
                This invoice's target upload folder:
            </div>
            <div class="bg-light p-3 rounded border text-break mb-3">
                <code class="text-success"><i class="fas fa-folder me-1"></i>{{ $invoice->google_drive_path ?? '/QueueBill/Invoices' }}</code>
            </div>
            <div class="fs-8 text-muted">
                <i class="fas fa-info-circle me-1"></i> Drive paths are registered during recurring service scheduling. Any revisions trigger an automatic file sync simulation to this folder.
            </div>
        </div>
    </div>
</div>
@endsection
