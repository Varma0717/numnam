@extends('store.layouts.app')
@section('title', 'Reset Password — NumNam')

@section('content')
<section class="auth-section section-pad">
    <div class="store-container">
        <div class="auth-card animate-fade-up">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your new password below.</p>

            <form method="POST" action="{{ route('store.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="input" required
                        value="{{ old('email', $email) }}">
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="input" required minlength="8">
                    @error('password') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
                </div>

                <button type="submit" class="btn-primary btn-full">Reset Password</button>
            </form>
        </div>
    </div>
</section>
@endsection