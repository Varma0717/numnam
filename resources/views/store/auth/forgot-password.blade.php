@extends('store.layouts.app')
@section('title', 'Forgot Password — NumNam')

@section('content')
<section class="auth-section section-pad">
    <div class="store-container">
        <div class="auth-card animate-fade-up">
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-subtitle">Enter your email and we'll send you a reset link.</p>

            @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('store.password.email') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="input" required autofocus
                        value="{{ old('email') }}" placeholder="you@example.com">
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary btn-full">Send Reset Link</button>
            </form>

            <p class="auth-footer-link">
                <a href="{{ route('store.login') }}">Back to Login</a>
            </p>
        </div>
    </div>
</section>
@endsection