@extends('store.layouts.app')

@section('title', 'NumNam - Register')

@section('content')
<section class="section auth-card">
    <div class="section-head"><div><h3>Create Account</h3><p class="section-sub">Join the NumNam commerce portal</p></div></div>
    <form method="POST" action="{{ route('store.register.submit') }}" class="form-grid">
        @csrf
        <input class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required>
        <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        <input class="input" type="password" name="password" placeholder="Password" required>
        <input class="input" type="password" name="password_confirmation" placeholder="Confirm password" required>
        <input class="input" type="text" name="referral_code" value="{{ old('referral_code', $ref) }}" placeholder="Referral code (optional)">
        <button class="cta-btn" type="submit">Register</button>
        <p class="meta">Already have an account? <a href="{{ route('store.login') }}">Login</a></p>
    </form>
</section>
@endsection
