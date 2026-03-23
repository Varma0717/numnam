@extends('store.layouts.app')

@section('title', 'NumNam - Login')

@section('content')
<section class="section auth-card">
    <div class="section-head"><div><h3>Customer Login</h3><p class="section-sub">Use your account credentials</p></div></div>
    <form method="POST" action="{{ route('store.login.submit') }}" class="form-grid">
        @csrf
        <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        <input class="input" type="password" name="password" placeholder="Password" required>
        <label class="meta"><input type="checkbox" name="remember"> Remember me</label>
        <button class="cta-btn" type="submit">Login</button>
        <p class="meta">New user? <a href="{{ route('store.register') }}">Create account</a></p>
    </form>
</section>
@endsection
