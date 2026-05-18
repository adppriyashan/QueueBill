@extends('layouts.app')

@section('title', 'Preview Next Invoice: ' . $service->name . ' - QueueBill')
@section('page_title', 'Next Statement Preview')

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
        $templateTitle = $service->invoiceStructureTemplate?->title ?? '';
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
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        padding: 6px 14px;
        border-radius: 50rem;
        font-weight: 700;
        font-size: 0.75rem;
        backdrop-filter: blur(8px);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        /* Hide everything except the invoice sheet */
        .sidebar, .navbar, .alert, .btn, button, a, footer {
            display: none !important;
        }
        .col-12, .col-xl-10 {
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
<div class="row g-4 justify-content-center">
    <div class="col-12 col-xl-10">
        <!-- Preview Alert Indicator -->
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4 p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-warning-soft text-warning me-3">
                    <i class="fas fa-eye fa-2x"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Pre-emptive Cycle Invoice Preview</h5>
                    <p class="mb-0 text-secondary fs-7">This is a dynamic projection of the statement compiled for the upcoming billing run scheduled on <strong>{{ $periodFrom->format('M d, Y') }}</strong>.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm rounded-3 px-3"><i class="fas fa-print me-2"></i>Print Preview</button>
                <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary btn-sm rounded-3"><i class="fas fa-arrow-left me-2"></i>Back to Contract</a>
            </div>
        </div>

        <div class="invoice-sheet mb-4">
            <!-- Sheet Header -->
            <div class="sheet-header-bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <span class="sheet-badge mb-2 d-inline-block"><i class="fas fa-magic me-1"></i>Next Cycle Forecast</span>
                    <h2 class="fw-bold tracking-tight mb-1">{{ auth()->user()->company_name ?? 'QueueBill Automation System' }}</h2>
                    <p class="fs-8 mb-0 opacity-75">Layout branded via template: <strong>{{ $service->invoiceStructureTemplate?->title ?? 'Default Template' }}</strong></p>
                </div>
                <div class="text-md-end">
                    <h3 class="fw-bold mb-0">PRO-FORMA</h3>
                    <span class="fs-6 opacity-75">#{{ $invoiceNumber }}</span>
                </div>
            </div>

            <!-- Sheet Body -->
            <div class="sheet-body">
                <!-- Meta Rows -->
                <div class="row g-4 mb-5 fs-7">
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed From</h6>
                        <strong class="text-dark d-block">{{ auth()->user()->company_name ?? 'QueueBill Automation System' }}</strong>
                        <span class="text-muted d-block">{!! nl2br(e(auth()->user()->company_address ?? "100 Revenue Way, Suite A\nAustin, TX 78701")) !!}</span>
                        
                        <!-- Custom Fallback Email overridden via template properties -->
                        <span class="text-primary fw-medium d-block mt-2">
                            <i class="far fa-envelope me-1"></i>
                            @php
                                $senderEmail = $service->invoiceStructureTemplate?->sender_email;
                            @endphp
                            @if($senderEmail)
                                <strong>{{ $senderEmail }}</strong> <small class="text-muted">(Override)</small>
                            @else
                                hello@queuebill.com <small class="text-muted">(System Default)</small>
                            @endif
                        </span>
                    </div>
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed To</h6>
                        <strong class="text-dark d-block">{{ $service->company->name }}</strong>
                        <span class="text-muted d-block">{{ $service->company->address ?? '—' }}</span>
                        <span class="text-primary fw-medium d-block mt-2">
                            <i class="far fa-envelope me-1"></i>{{ $service->company->email }}
                        </span>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Cycle Timeline</h6>
                        <div class="mb-1"><strong>Forecast Date:</strong> <span class="text-muted">{{ $periodFrom->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Due Date:</strong> <span class="text-muted">{{ $periodFrom->copy()->addDays(14)->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Period From:</strong> <span class="text-muted">{{ $periodFrom->format('M d, Y') }}</span></div>
                        <div class="mb-1"><strong>Period To:</strong> <span class="text-muted">{{ $periodTo->format('M d, Y') }}</span></div>
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
                                $itemsCollect = collect($items);
                                $baseItem = $itemsCollect->first(function($item) {
                                    return !$item->is_adhoc && str_contains(strtolower($item->description), 'base subscription');
                                });
                                if (!$baseItem) {
                                    $baseItem = $itemsCollect->first(function($item) {
                                        return !$item->is_adhoc && $item->amount > 0;
                                    });
                                }
                                
                                $scopeItems = $itemsCollect->filter(function($item) use ($baseItem) {
                                    return !$item->is_adhoc && $item->amount == 0;
                                });
                                
                                $otherItems = $itemsCollect->filter(function($item) use ($baseItem, $scopeItems) {
                                    $excludeDesc = [];
                                    if ($baseItem) $excludeDesc[] = $baseItem->description;
                                    foreach ($scopeItems as $si) $excludeDesc[] = $si->description;
                                    return !in_array($item->description, $excludeDesc);
                                });
                            @endphp

                            @if($baseItem)
                                <tr>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $baseItem->description }}</div>
                                        <span class="badge bg-primary-soft text-primary fs-9 px-2 py-0.5 mt-1 d-inline-block">Standard Base Cost</span>
                                        @if($scopeItems->isNotEmpty())
                                            <div class="mt-3 text-secondary">
                                                <div class="fw-bold fs-8 text-uppercase tracking-wider mb-1" style="font-size: 0.65rem; letter-spacing: 0.05em;">Included Contract Scope Elements:</div>
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
                                        @currency($baseItem->amount)
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
                                            <span class="text-success">-@currency(abs($item->amount))</span>
                                        @else
                                            @currency($item->amount)
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals row -->
                <div class="row justify-content-end text-md-end fs-7">
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-secondary fw-semibold">Subtotal:</span>
                            <span class="fw-bold text-dark">@currency($subtotal)</span>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom fs-6">
                            <span class="text-dark fw-bold">Total Forecast Due:</span>
                            <span class="fw-extrabold text-theme-price fs-4 fw-bold">@currency($total)</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-muted fs-8 font-italic">"Your Recurring Revenue, Perfectly Aligned."</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
