@extends('layouts.auth')

@section('title', 'Login - QueueBill')

@section('content')
    <div class="card p-4 p-md-5">
        <div class="text-center mb-4">
            <h1 class="brand-logo mb-1">QueueBill</h1>
            <p class="text-muted fs-9">Your Recurring Revenue, Perfectly Aligned.</p>
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

            <div class="mb-3">
                <label for="email" class="form-label text-secondary fw-semibold fs-7">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="far fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="admin@queuebill.com">
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label text-secondary fw-semibold fs-7 mb-0">Password</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                    <input id="password" type="password"
                        class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password" placeholder="••••••••">
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label fs-7 text-secondary" for="remember">
                        Remember Me
                    </label>
                </div>
            </div>

            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-md">
                    Sign In
                </button>
            </div>

            <div class="text-center">
                <p class="text-muted fs-7 mb-0">
                    Don't have an administrative account? <a href="{{ route('register') }}"
                        class="text-primary fw-semibold text-decoration-none">Sign Up</a>
                </p>
            </div>
        </form>
    </div>
@endsection