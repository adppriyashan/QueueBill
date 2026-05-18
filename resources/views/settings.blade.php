@extends('layouts.app')

@section('title', 'System Settings - QueueBill')
@section('page_title', 'System settings')

@section('content')
<!-- Header Controls -->
<div class="card mb-4 border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div>
            <h5 class="fw-bold mb-1">QueueBill Configurations</h5>
            <p class="text-secondary mb-0 fs-7">Manage system-wide alignment rules, defaults, and multi-tenant parameters.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <!-- Settings Form -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-primary-soft text-primary me-3">
                        <i class="fas fa-sliders-h fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">System & Company Settings</h5>
                        <p class="text-secondary mb-0 fs-8">Configure your company identity, addresses, and system-wide default currency symbol.</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="currency" class="form-label text-secondary fw-semibold fs-7">Select Currency Symbol</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-coins"></i></span>
                            <select id="currency" name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                <option value="$" {{ old('currency', $user->currency) === '$' ? 'selected' : '' }}>$ - Dollar (USD, CAD, AUD)</option>
                                <option value="€" {{ old('currency', $user->currency) === '€' ? 'selected' : '' }}>€ - Euro (EUR)</option>
                                <option value="£" {{ old('currency', $user->currency) === '£' ? 'selected' : '' }}>£ - Pound (GBP)</option>
                                <option value="¥" {{ old('currency', $user->currency) === '¥' ? 'selected' : '' }}>¥ - Yen/Yuan (JPY, CNY)</option>
                                <option value="Rs." {{ old('currency', $user->currency) === 'Rs.' ? 'selected' : '' }}>Rs. - Rupee (INR, PKR)</option>
                                <option value="LKR" {{ old('currency', $user->currency) === 'LKR' ? 'selected' : '' }}>LKR - Sri Lankan Rupee (LKR)</option>
                                <option value="රු." {{ old('currency', $user->currency) === 'රු.' ? 'selected' : '' }}>රු. - Sri Lankan Rupee (රු.)</option>
                                <option value="RM" {{ old('currency', $user->currency) === 'RM' ? 'selected' : '' }}>RM - Ringgit (MYR)</option>
                                <option value="AED" {{ old('currency', $user->currency) === 'AED' ? 'selected' : '' }}>AED - Dirham (AED)</option>
                                <option value="CHF" {{ old('currency', $user->currency) === 'CHF' ? 'selected' : '' }}>CHF - Franc (CHF)</option>
                            </select>
                        </div>
                        @error('currency')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="company_name" class="form-label text-secondary fw-semibold fs-7">Billed From: Company Name <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted"><i class="fas fa-building"></i></span>
                            <input type="text" id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $user->company_name) }}" required placeholder="e.g. QueueBill Automation System">
                        </div>
                        @error('company_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="company_address" class="form-label text-secondary fw-semibold fs-7">Billed From: Company Address <span class="text-danger">*</span></label>
                        <textarea id="company_address" name="company_address" class="form-control @error('company_address') is-invalid @enderror" rows="3" required placeholder="e.g.&#10;100 Revenue Way, Suite A&#10;Austin, TX 78701">{{ old('company_address', $user->company_address) }}</textarea>
                        @error('company_address')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr class="my-4 text-muted opacity-25">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Profile & Password Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-primary-soft text-primary me-3">
                        <i class="fas fa-user-cog fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">My Profile & Password</h5>
                        <p class="text-secondary mb-0 fs-8">Manage your account profile details and security credentials.</p>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('settings.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="name" class="form-label text-secondary fw-semibold fs-7">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fas fa-user"></i></span>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block fs-8">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label text-secondary fw-semibold fs-7 text-muted">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="email" name="email" class="form-control bg-light text-muted" value="{{ $user->email }}" readonly disabled>
                            </div>
                            <div class="form-text text-muted fs-8"><i class="fas fa-info-circle me-1"></i> Email address cannot be changed.</div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="password" class="form-label text-secondary fw-semibold fs-7">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="•••••••• (Leave blank to keep current)">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block fs-8">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <label for="password_confirmation" class="form-label text-secondary fw-semibold fs-7">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fas fa-shield-alt"></i></span>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4 text-muted opacity-25">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Save Account Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <!-- Google Drive Integration Card -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-2 bg-success-soft text-success me-3">
                        <i class="fab fa-google-drive fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Google Drive</h5>
                        <p class="text-secondary mb-0 fs-8">Automated statement backups</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @if($user->google_access_token)
                    <div class="alert alert-success d-flex align-items-center mb-3 fs-7" role="alert" style="background-color: rgba(25, 135, 84, 0.1); color: #198754; border: 0;">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            Connected to <strong class="text-dark">{{ $user->google_email }}</strong>
                        </div>
                    </div>
                    <form action="{{ route('auth.google.disconnect') }}" method="POST" onsubmit="return confirm('Disconnect Google Drive?')">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                            <i class="fas fa-unlink me-1"></i> Disconnect Drive
                        </button>
                    </form>
                @else
                    <p class="fs-7 text-secondary mb-3">Sync generated billing invoices directly to your personal or team Google Drive paths automatically.</p>
                    <a href="{{ route('auth.google') }}" class="btn btn-success btn-sm w-100 text-white">
                        <i class="fab fa-google me-1"></i> Connect Google Drive
                    </a>
                @endif
            </div>
        </div>

        <!-- Help Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white p-4" style="background: var(--primary-gradient) !important;">
            <div class="d-flex flex-column justify-content-between">
                <div>
                    <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Dynamic Currency</h5>
                    <p class="fs-7 opacity-75 mb-3">Changing this setting updates all monetary values in real-time:</p>
                    <ul class="fs-7 opacity-75 ps-3 mb-0">
                        <li class="mb-2">Admin Dashboard reports & total rollups.</li>
                        <li class="mb-2">Subscription services contract directories.</li>
                        <li class="mb-2">Generated PDF statements & live template previews.</li>
                        <li class="mb-2">System broadcast logs & Google Drive uploads.</li>
                    </ul>
                </div>
                <div class="mt-4 pt-3 border-top border-white-50">
                    <small class="fs-8 opacity-50 d-block">Active Session: {{ $user->name }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
