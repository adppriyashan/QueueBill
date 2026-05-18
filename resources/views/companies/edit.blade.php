@extends('layouts.app')

@section('title', 'Edit Company - QueueBill')
@section('page_title', 'Edit Company Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-2 bg-warning-soft text-warning me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Update Company: {{ $company->name }}</h5>
                            <p class="text-secondary mb-0 fs-7">Modify profile details, emails, and directory status flags.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Logo Preview & Field -->
                        @if($company->logo)
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-semibold fs-7 d-block">Current Logo</label>
                                <div class="p-2 border rounded bg-light d-inline-block">
                                    <img src="{{ asset($company->logo) }}" alt="Current Logo" style="height: 60px; max-width: 150px; object-fit: contain;">
                                </div>
                            </div>
                        @endif

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
                                name="name" value="{{ old('name', $company->name) }}" required
                                placeholder="e.g. Acme Corporation">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label text-secondary fw-semibold fs-7">Billing Email Address
                                <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $company->email) }}" required
                                placeholder="e.g. billing@acme.com">
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
                                name="phone" value="{{ old('phone', $company->phone) }}"
                                placeholder="e.g. +1 (555) 019-2834">
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
                                placeholder="e.g. 123 Industrial Way, Suite 400, Austin, TX 78701">{{ old('address', $company->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label for="status" class="form-label text-secondary fw-semibold fs-7">Billing Directory Status
                                <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                required>
                                <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>
                                    Active (Enabled for billing)</option>
                                <option value="inactive" {{ old('status', $company->status) === 'inactive' ? 'selected' : '' }}>Inactive (Suspended from cycles)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-4">
                            <a href="{{ route('companies.index') }}" class="btn btn-light btn-sm border">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection