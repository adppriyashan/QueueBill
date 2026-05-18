@extends('layouts.app')

@section('title', 'Administrative Dashboard - QueueBill')
@section('page_title', 'Administrative Console')

@section('content')
<!-- Welcome Banner -->
<div class="card mb-4 border-0 shadow-sm rounded-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
    <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
        <div>
            <h2 class="fw-bold tracking-tight mb-2">Welcome to QueueBill Console</h2>
            <p class="mb-0 fs-6 opacity-75">"Your Recurring Revenue, Perfectly Aligned."</p>
        </div>
        <div class="badge bg-white text-primary fw-bold px-4 py-3 rounded-4 fs-7 shadow-sm">
            <i class="far fa-clock me-2"></i>{{ date('l, M d, Y') }}
        </div>
    </div>
</div>

<!-- Metrics Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-primary-soft text-primary me-3">
                    <i class="fas fa-building fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Buyer Directory</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $companiesCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-success-soft text-success me-3">
                    <i class="fas fa-sync fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Active Subscriptions</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $activeSchedulesCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card card-hover h-100 p-3 bg-white">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-3 bg-info-soft text-info me-3">
                    <i class="fas fa-dollar-sign fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-secondary fw-semibold mb-1 fs-7">Aggregate Invoiced</h6>
                    <h3 class="fw-bold mb-0 text-dark">${{ number_format($totalInvoiced, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sandbox Command simulation panel -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-bottom p-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-3 p-2 bg-warning-soft text-warning me-3">
                    <i class="fas fa-server fa-lg"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">QueueBill Simulation Sandbox</h5>
                    <p class="text-secondary mb-0 fs-7">Manually execute periodic automated billing runs without accessing CLI schedulers.</p>
                </div>
            </div>
            <span class="badge bg-warning text-dark px-3 py-1.5 rounded-3 fs-8 text-uppercase"><i class="fas fa-shield-alt me-1"></i>Simulation</span>
        </div>
    </div>
    <div class="card-body p-4">
        @if(session('cron_output'))
            <div class="mb-4">
                <h6 class="fw-bold text-dark mb-2">Simulated Artisan Engine Log Output:</h6>
                <pre class="bg-dark text-success p-3 rounded-3 border fs-8 mb-0" style="max-height: 250px; overflow-y: auto; font-family: monospace;">{{ session('cron_output') }}</pre>
            </div>
        @endif

        <form method="POST" action="{{ route('sandbox.cron') }}" class="p-3 bg-light rounded-4 border">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label for="simulation_date" class="form-label text-secondary fw-semibold fs-7 mb-2">Set Simulation Run Target Date <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="far fa-calendar-alt text-muted"></i></span>
                        <input type="date" class="form-control" id="simulation_date" name="simulation_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-warning w-100 py-2.5 fw-semibold fs-7"><i class="fas fa-play me-2"></i>Tick Midnight Cron Run</button>
                </div>
                <div class="col-12 col-md-3">
                    <div class="fs-8 text-muted mt-2 mt-md-0"><i class="fas fa-info-circle me-1"></i> Running advances billing cycles, processes pending ad-hoc injections, and logs simulated transactions.</div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Upcoming schedules -->
    <div class="col-12 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Upcoming Billings Queue</h5>
                <a href="{{ route('services.index') }}" class="fs-7 text-primary text-decoration-none hover-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle fs-7">
                        <thead>
                            <tr>
                                <th>Subscriber</th>
                                <th>Next Date</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeServices as $service)
                                <tr>
                                    <td>
                                        <div>
                                            <a href="{{ route('services.show', $service) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $service->name }}</a>
                                            <span class="text-muted d-block fs-8">{{ $service->company->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $service->next_billing_date->format('M d, Y') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($service->base_cost, 2) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted fs-7">No active schedules queue.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent invoices -->
    <div class="col-12 col-xl-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Generated Statement Ledger</h5>
                <a href="{{ route('invoices.index') }}" class="fs-7 text-primary text-decoration-none hover-primary">View All Ledger</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0 align-middle fs-7">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Buyer Company</th>
                                <th>Amount</th>
                                <th>Version</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <div>
                                            <a href="{{ route('invoices.show', $invoice) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $invoice->invoice_number }}</a>
                                            <span class="text-muted d-block fs-8">{{ $invoice->issue_date->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('companies.show', $invoice->company) }}" class="text-secondary text-decoration-none hover-primary">{{ $invoice->company->name }}</a>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">${{ number_format($invoice->total, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($invoice->version > 1)
                                            <span class="badge bg-warning text-dark font-weight-bold">v{{ $invoice->version }}</span>
                                        @else
                                            <span class="badge bg-light text-dark border">v1</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($invoice->uploaded_to_drive_at)
                                            <span class="badge-premium bg-success-soft text-success"><i class="fab fa-google-drive me-1"></i>Saved</span>
                                        @else
                                            <span class="badge-premium bg-light text-muted"><i class="fas fa-minus me-1"></i>No Path</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted fs-7">No statement ledger generated yet.</td>
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
