@extends('store.layouts.app')

@section('title', 'NumNam - Register')

@section('content')
<section class="section auth-card">
    <h2>Create Account</h2>
    <p class="meta">Join the NumNam commerce portal</p>
    <form method="POST" action="{{ route('store.register.submit') }}">
        @csrf
        <div class="form-group">
            <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required>
        </div>
        <div class="form-group">
            <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input class="input" type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <input class="input" type="password" name="password_confirmation" placeholder="Confirm password" required>
        </div>
        <div class="form-group">
            <input class="input" type="text" name="referral_code" value="{{ old('referral_code', $ref) }}" placeholder="Referral code (optional)">
        </div>
        <button class="btn btn-primary" type="submit">Register</button>
        <p class="meta">Already have an account? <a href="{{ route('store.login') }}">Login</a></p>
    </form>
</section>
@endsection