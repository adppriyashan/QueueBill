@extends('layouts.auth')

@section('title', 'Login - QueueBill')

@section('content')
    <div class="card p-4 p-md-5">
        <div class="text-center mb-4">
            <h1 class="brand-logo mb-1">QueueBill</h1>
            <small class="text-muted">Your Recurring Revenue, Perfectly Aligned.</small>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4" role="alert">
                <ul class="mb-0 fs-7">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 animate-fade-in stagger-1">
                <label for="email" class="form-label text-secondary fw-semibold fs-7">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="far fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="user@queuebill.com">
                </div>
            </div>

            <div class="mb-4 animate-fade-in stagger-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label text-secondary fw-semibold fs-7 mb-0">Password</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="••••••••">
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center animate-fade-in stagger-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label fs-7 text-secondary" for="remember">
                        Remember Me
                    </label>
                </div>
            </div>

            <div class="d-grid mb-4 animate-fade-in stagger-4">
                <button type="submit" class="btn btn-primary btn-sm">
                    Sign In
                </button>
            </div>

            <div class="text-center animate-fade-in stagger-5">
                <p class="text-muted fs-7 mb-0">
                    <small>Don't have an account? <a href="{{ route('register') }}"
                            class="text-dark fw-semibold text-decoration-none"> Create Yours ..</a></small>
                </p>
            </div>
        </form>
    </div>
@endsection