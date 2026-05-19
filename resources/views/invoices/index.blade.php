@extends('layouts.app')

@section('title', 'Generated Invoices Directory - QueueBill')
@section('page_title', 'Generated Invoice Tracking')

@section('content')
    <!-- Metrics Dashboard Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card card-hover h-100 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-3 bg-primary-soft text-primary me-3">
                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary fw-semibold mb-1 fs-7">Total Generated Invoices</h6>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalInvoices }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card card-hover h-100 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-3 bg-success-soft text-success me-3">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary fw-semibold mb-1 fs-7">Total Revenue Invoiced</h6>
                        <h3 class="fw-bold mb-0 text-dark">@currency($totalBilled)</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card card-hover h-100 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-3 bg-info-soft text-info me-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary fw-semibold mb-1 fs-7">Average Invoice Total</h6>
                        <h3 class="fw-bold mb-0 text-dark">@currency($averageInvoice)</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid List -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom p-4">
            <h5 class="fw-bold mb-0">Invoice Ledger Listing</h5>
            <p class="text-secondary mb-0 fs-7">Browse generated cycle invoices, versions, simulated Google Drive uploads,
                and post-billing adjustments.</p>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Invoice Number</th>
                            <th>Buyer Company</th>
                            <th>Billing Contract</th>
                            <th>Cycle Period</th>
                            <th>Billed Amount</th>
                            <th>Revision Version</th>
                            <th>Drive Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
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
                                    <div>
                                        <a href="{{ route('companies.show', $invoice->company) }}"
                                            class="fw-semibold text-secondary text-decoration-none hover-primary fs-7">{{ $invoice->company->name }}</a>
                                    </div>
                                </td>
                                <td>
                                    @if($invoice->recurringService)
                                        <a href="{{ route('services.show', $invoice->recurringService) }}"
                                            class="text-primary text-decoration-none hover-dark fs-7">{{ $invoice->recurringService->name }}</a>
                                    @else
                                        <span class="text-muted fs-8 font-italic">Independent / Deleted Contract</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fs-8 text-secondary">{{ $invoice->period_from->format('M d') }} -
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
                                <td>
                                    @if($invoice->uploaded_to_drive_at)
                                        <span class="badge-premium bg-success-soft text-success text-truncate d-inline-block"
                                            style="max-width: 140px;" title="Google Drive: {{ $invoice->google_drive_path }}">
                                            <i class="fab fa-google-drive me-1"></i>Uploaded
                                        </span>
                                    @else
                                        <span class="badge-premium bg-light text-muted"><i class="fas fa-minus me-1"></i>No
                                            Path</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm"
                                        title="Review Invoice Details"><i class="far fa-eye me-1"></i>View / Revise</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-secondary opacity-50"></i>
                                        <p class="mb-0 fw-semibold">No invoices have been generated yet.</p>
                                        <p class="fs-7">Billing cycles occur automatically at midnight, or can be triggered
                                            manually from the Sandbox.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="px-4 py-3 border-top bg-light">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection