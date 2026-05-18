@extends('layouts.auth')

@section('title', 'Register - QueueBill')

@section('content')
<div class="card p-4 p-md-5">
    <div class="text-center mb-4">
        <h1 class="brand-logo mb-1">QueueBill</h1>
        <p class="text-muted fs-7">"Your Recurring Revenue, Perfectly Aligned."</p>
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

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-secondary fw-semibold fs-7">Full Name</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="far fa-user"></i></span>
                <input id="name" type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Alexander Pierce">
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-secondary fw-semibold fs-7">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="far fa-envelope"></i></span>
                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="alex@queuebill.com">
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-secondary fw-semibold fs-7">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>

        <div class="mb-4">
            <label for="password-confirm" class="form-label text-secondary fw-semibold fs-7">Confirm Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                <input id="password-confirm" type="password" class="form-control border-start-0" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            </div>
        </div>

        <div class="d-grid mb-4">
            <button type="submit" class="btn btn-primary btn-lg">
                Create Account
            </button>
        </div>

        <div class="text-center">
            <p class="text-muted fs-7 mb-0">
                Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Sign In</a>
            </p>
        </div>
    </form>
</div>
@endsection
