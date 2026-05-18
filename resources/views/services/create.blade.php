@extends('layouts.app')

@section('title', 'Schedule Subscription Service - QueueBill')
@section('page_title', 'Create Billing Schedule')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-primary-soft text-primary me-3">
                        <i class="fas fa-calendar-plus fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Subscription Configuration</h5>
                        <p class="text-secondary mb-0 fs-7">Connect targeted buyer entities seamlessly into automated pricing structures.</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form method="POST" action="{{ route('services.store') }}">
                    @csrf

                    <!-- Company Selector -->
                    <div class="mb-4">
                        <label for="company_id" class="form-label text-secondary fw-semibold fs-7">Target Buyer Company <span class="text-danger">*</span></label>
                        <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                            <option value="">-- Choose Buyer Company --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $selected_company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }} ({{ $company->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Selector -->
                    <div class="mb-4">
                        <label for="invoice_structure_template_id" class="form-label text-secondary fw-semibold fs-7">Invoice Layout Structure Template <span class="text-danger">*</span></label>
                        <select class="form-select @error('invoice_structure_template_id') is-invalid @enderror" id="invoice_structure_template_id" name="invoice_structure_template_id" required>
                            <option value="">-- Choose Structure Template --</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('invoice_structure_template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->title }} (Slug: {{ $template->slug }})
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_structure_template_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Service / Sub Name -->
                    <div class="mb-4">
                        <label for="name" class="form-label text-secondary fw-semibold fs-7">Subscription Name / Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. SaaS Cloud Hosting Enterprise Suite">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Calendar Boundaries (From and To date) -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label for="from_date" class="form-label text-secondary fw-semibold fs-7">Cycle Start Date (from_date) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('from_date') is-invalid @enderror" id="from_date" name="from_date" value="{{ old('from_date', date('Y-m-d')) }}" required>
                            @error('from_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="to_date" class="form-label text-secondary fw-semibold fs-7">Contract Expiration Date (to_date) <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('to_date') is-invalid @enderror" id="to_date" name="to_date" value="{{ old('to_date', date('Y-m-d', strtotime('+1 year'))) }}" required>
                            @error('to_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Recurrence Cadence & Base Cost -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6">
                            <label for="recurring_cadence" class="form-label text-secondary fw-semibold fs-7">Recurring Cadence Frequency <span class="text-danger">*</span></label>
                            <select class="form-select @error('recurring_cadence') is-invalid @enderror" id="recurring_cadence" name="recurring_cadence" required>
                                <option value="month" {{ old('recurring_cadence') === 'month' ? 'selected' : '' }}>Month</option>
                                <option value="6_months" {{ old('recurring_cadence') === '6_months' ? 'selected' : '' }}>6 Months</option>
                                <option value="year" {{ old('recurring_cadence') === 'year' ? 'selected' : '' }}>Year</option>
                            </select>
                            @error('recurring_cadence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="base_cost" class="form-label text-secondary fw-semibold fs-7">Financial Base Cost Rate ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control @error('base_cost') is-invalid @enderror" id="base_cost" name="base_cost" value="{{ old('base_cost', '0.00') }}" required placeholder="0.00">
                            </div>
                            @error('base_cost')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Scope Elements (Invoice Includes) -->
                    <div class="mb-4">
                        <label for="invoice_includes" class="form-label text-secondary fw-semibold fs-7">Scope Elements (invoice_includes) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('invoice_includes') is-invalid @enderror" id="invoice_includes" name="invoice_includes" rows="5" required placeholder="e.g.&#10;Unlimited Corporate Cloud Storage Access&#10;10 Dedicated Server Workspaces&#10;24/7 Premium Security Monitoring Service">{{ old('invoice_includes') }}</textarea>
                        @error('invoice_includes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted fs-8">Text block parsed directly line-by-line into structural layout designs. Add each item/line on a new line.</div>
                    </div>

                    <!-- Google Drive path -->
                    <div class="mb-4">
                        <label for="google_drive_path" class="form-label text-secondary fw-semibold fs-7">Google Drive Upload Directory Path (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text text-success bg-white border-end-0"><i class="fab fa-google-drive"></i></span>
                            <input type="text" class="form-control border-start-0 @error('google_drive_path') is-invalid @enderror" id="google_drive_path" name="google_drive_path" value="{{ old('google_drive_path') }}" placeholder="e.g. /QueueBill/Invoices/AcmeCorp">
                        </div>
                        @error('google_drive_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted fs-8">When configured, all generated invoices during automation runs are simulated as uploaded to this target directory in Google Drive.</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label text-secondary fw-semibold fs-7">Billing Cycle Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="active" {{ old('status') === 'active' || !old('status') ? 'selected' : '' }}>Active (Cycle runner active)</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive (Cycle suspended)</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4">
                        <a href="{{ route('companies.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Billing Contract</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
