@extends('layouts.app')

@section('title', 'Register Company - QueueBill')
@section('page_title', 'Register New Company')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-2 bg-primary-soft text-primary me-3">
                            <i class="fas fa-plus-circle fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Company Profile Details</h5>
                            <p class="text-secondary mb-0 fs-7">Register a buyer entity for automated recurring billing
                                schedules.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Logo Field -->
                        <div class="mb-4">
                            <label for="logo" class="form-label text-secondary fw-semibold fs-7">Company Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted fs-8">Provide an image logo for client-branded statement headers.</div>
                        </div>

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label text-secondary fw-semibold fs-7">Company Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required placeholder="e.g. Acme Corporation">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label text-secondary fw-semibold fs-7">Billing Email Address
                                <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required placeholder="e.g. billing@acme.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted fs-8">Used directly to dispatch monthly invoices and revisions.
                            </div>
                        </div>

                        <!-- Phone Field -->
                        <div class="mb-4">
                            <label for="phone" class="form-label text-secondary fw-semibold fs-7">Billing Contact Phone
                                (Optional)</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}" placeholder="e.g. +1 (555) 019-2834">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="mb-4">
                            <label for="address" class="form-label text-secondary fw-semibold fs-7">Billing Address
                                (Optional)</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address"
                                name="address" rows="4"
                                placeholder="e.g. 123 Industrial Way, Suite 400, Austin, TX 78701">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label for="status" class="form-label text-secondary fw-semibold fs-7">Initial Status <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="active" {{ old('status') === 'active' || !old('status') ? 'selected' : '' }}>
                                    Active (Enabled for billing)</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                    (Suspended from cycles)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('companies.index') }}" class="btn btn-light border btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection