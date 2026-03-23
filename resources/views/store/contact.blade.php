@extends('store.layouts.app')

@section('title', 'NumNam - Contact')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Support</span>
        <h1>Contact the NumNam care team</h1>
        <p>Messages are persisted in Laravel and available in admin contact management.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <form class="form-grid" method="POST" action="{{ route('store.contact.submit') }}">
        @csrf
        <div class="store-grid two">
            <input class="input" name="first_name" placeholder="Name" required>
            <input class="input" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="store-grid two">
            <input class="input" name="phone" placeholder="Phone">
            <select class="input" name="query_type" required>
                <option value="general">General</option>
                <option value="order">Order</option>
                <option value="wholesale">Wholesale</option>
                <option value="press">Press</option>
                <option value="other">Other</option>
            </select>
        </div>
        <input class="input" name="company" placeholder="Company (optional)">
        <textarea name="message" placeholder="How can we help?" required></textarea>
        <button class="cta-btn" type="submit">Send message</button>
    </form>
</section>
@endsection
