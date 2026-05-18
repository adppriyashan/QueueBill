@extends('layouts.app')

@section('title', $company->name . ' - Historical Billing Profile - QueueBill')
@section('page_title', 'Historical Billing Profile: ' . $company->name)

@section('content')
<div class="row g-4 mb-4">
    <!-- Profile Card (Left Column) -->
    <div class="col-12 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <h5 class="fw-bold mb-0">Company Profile Info</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="avatar-ring bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px; font-weight: 700; font-size: 1.25rem;">
                        {{ strtoupper(substr($company->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">{{ $company->name }}</h4>
                        @if($company->status === 'active')
                            <span class="badge-premium bg-success-soft text-success d-inline-block mt-1"><i class="fas fa-circle fs-8 me-1"></i>Active</span>
                        @else
                            <span class="badge-premium bg-danger-soft text-danger d-inline-block mt-1"><i class="fas fa-circle fs-8 me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="border-top pt-3">
                    <div class="mb-3">
                        <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Billing Email</label>
                        <a href="mailto:{{ $company->email }}" class="fw-medium text-dark text-decoration-none"><i class="far fa-envelope me-2 text-muted"></i>{{ $company->email }}</a>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Billing Phone</label>
                        <span class="fw-medium text-dark"><i class="fas fa-phone-alt me-2 text-muted"></i>{{ $company->phone ?? '—' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted fs-8 text-uppercase fw-semibold d-block">Physical Address</label>
                        <span class="fw-medium text-dark d-flex align-items-start"><i class="fas fa-map-marker-alt mt-1 me-2 text-muted"></i><span>{!! nl2br(e($company->address ?? '—')) !!}</span></span>
                    </div>
                </div>

                <div class="border-top pt-3 mt-3 fs-8 text-muted">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Created By:</span>
                        <span class="fw-medium text-dark">{{ $company->creator?->name ?? 'System' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Last Updated By:</span>
                        <span class="fw-medium text-dark">{{ $company->updater?->name ?? 'System' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Registered On:</span>
                        <span class="fw-medium text-dark">{{ $company->created_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light border-top p-3 text-center">
                <a href="{{ route('companies.edit', $company) }}" class="btn btn-light btn-sm border w-100"><i class="fas fa-edit me-1"></i>Edit Profile Info</a>
            </div>
        </div>
    </div>

    <!-- Active Recurring Subscriptions (Right Column) -->
    <div class="col-12 col-lg-8">
        <!-- Billing Metrics Aggregates -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6">
                <div class="card p-3 bg-white border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-primary-soft text-primary me-3">
                            <i class="fas fa-sync fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary fw-semibold mb-1 fs-7">Active Billing Schedules</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $company->recurringServices()->where('status', 'active')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div class="card p-3 bg-white border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 bg-success-soft text-success me-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-secondary fw-semibold mb-1 fs-7">Aggregate Billed Revenue</h6>
                            <h3 class="fw-bold mb-0 text-dark">${{ number_format($company->invoices()->sum('total'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Active Subscriptions Directory -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Linked Subscription Profiles</h5>
                <a href="{{ route('services.create', ['company_id' => $company->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Link New Schedule
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Cadence</th>
                                <th>Base Cost</th>
                                <th>Next Due</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->recurringServices as $service)
                                <tr>
                                    <td>
                                        <div>
                                            <a href="{{ route('services.show', $service) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">{{ $service->name }}</a>
                                            <span class="text-muted fs-8">Template: {{ $service->invoiceStructureTemplate->title }}</span>
                                            @if($service->google_drive_path)
                                                <small class="d-block text-secondary fs-8"><i class="fab fa-google-drive text-success me-1"></i>{{ $service->google_drive_path }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-soft text-secondary text-capitalize font-weight-semibold">{{ $service->recurring_cadence }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($service->base_cost, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-medium">{{ $service->next_billing_date->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        @if($service->status === 'active')
                                            <span class="badge-premium bg-success-soft text-success"><i class="fas fa-circle fs-8 me-1"></i>Active</span>
                                        @else
                                            <span class="badge-premium bg-danger-soft text-danger"><i class="fas fa-circle fs-8 me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('services.show', $service) }}" class="btn btn-light btn-sm border" title="Configure adjustments"><i class="fas fa-cog"></i> Adjustments</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted fs-7">
                                        No recurring subscription schedules linked.
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
                <h5 class="fw-bold mb-0">Generated Invoice Audit History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Invoice Number</th>
                                <th>Billing Period</th>
                                <th>Total Billed</th>
                                <th>Version</th>
                                <th>Drive Upload</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->invoices as $invoice)
                                <tr>
                                    <td>
                                        <div>
                                            <a href="{{ route('invoices.show', $invoice) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $invoice->invoice_number }}</a>
                                            <span class="text-muted d-block fs-8">Issued {{ $invoice->issue_date->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fs-7 text-secondary">{{ $invoice->period_from->format('M d') }} - {{ $invoice->period_to->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($invoice->total, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->version > 1)
                                            <span class="badge bg-warning text-dark font-weight-bold">v{{ $invoice->version }} (Revised)</span>
                                        @else
                                            <span class="badge bg-light text-dark border">v1</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->uploaded_to_drive_at)
                                            <span class="badge-premium bg-success-soft text-success text-truncate d-inline-block" style="max-width: 140px;" title="Uploaded to Google Drive: {{ $invoice->google_drive_path }} at {{ $invoice->uploaded_to_drive_at->format('M d, Y H:i') }}">
                                                <i class="fab fa-google-drive me-1"></i>Uploaded
                                            </span>
                                        @else
                                            <span class="badge-premium bg-light text-muted"><i class="fas fa-minus me-1"></i>No Path</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-primary btn-sm btn-primary-soft text-primary shadow-none border-0"><i class="far fa-eye me-1"></i>View / Revise</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted fs-7">
                                        No billing invoices have been generated for this client yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
