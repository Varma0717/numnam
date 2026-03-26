@extends('store.layouts.app')

@section('title', 'NumNam - Contact')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Support</span>
        <h1>Contact the NumNam care team</h1>
        <p>Messages are persisted in Laravel and available in admin contact management.</p>
    </div>
</section>

<section class="section animate-fade-up">
    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif
    <form class="form-grid" method="POST" action="{{ route('store.contact.submit') }}">
        @csrf
        <div class="store-grid two">
            <div>
                <label class="form-label" for="contact-name">Name <span style="color:var(--brand-3)">*</span></label>
                <input class="input input-glow" id="contact-name" name="first_name" placeholder="Your full name" required>
            </div>
            <div>
                <label class="form-label" for="contact-email">Email <span style="color:var(--brand-3)">*</span></label>
                <input class="input input-glow" id="contact-email" type="email" name="email" placeholder="you@example.com" required>
            </div>
        </div>
        <div class="store-grid two">
            <div>
                <label class="form-label" for="contact-phone">Phone</label>
                <input class="input input-glow" id="contact-phone" name="phone" placeholder="+91 00000 00000">
            </div>
            <div>
                <label class="form-label" for="contact-query">Query type <span style="color:var(--brand-3)">*</span></label>
                <select class="input input-glow" id="contact-query" name="query_type" required>
                    <option value="general">General</option>
                    <option value="order">Order</option>
                    <option value="wholesale">Wholesale</option>
                    <option value="press">Press</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <div>
            <label class="form-label" for="contact-company">Company <small>(optional)</small></label>
            <input class="input input-glow" id="contact-company" name="company" placeholder="Your company name">
        </div>
        <div>
            <label class="form-label" for="contact-message">Message <span style="color:var(--brand-3)">*</span></label>
            <textarea class="input input-glow" id="contact-message" name="message" rows="5" placeholder="How can we help?" required></textarea>
        </div>
        <button class="cta-btn btn-ripple" type="submit">Send message</button>
    </form>
</section>
@endsection