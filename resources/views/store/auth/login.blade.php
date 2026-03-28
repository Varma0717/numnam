@extends('store.layouts.app')

@section('title', 'NumNam - Login')

@section('content')
<section class="section auth-card">
    <h2>Customer Login</h2>
    <p class="meta">Use your account credentials</p>
    <form method="POST" action="{{ route('store.login.submit') }}">
        @csrf
        <div class="form-group">
            <input class="input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input class="input" type="password" name="password" placeholder="Password" required>
        </div>
        <label class="meta"><input type="checkbox" name="remember"> Remember me</label>
        <button class="btn btn-primary" type="submit">Login</button>
        <p class="meta">New user? <a href="{{ route('store.register') }}">Create account</a></p>
    </form>
</section>
@endsection