@extends('store.layouts.app')

@section('title', 'NumNam - FAQ')
@section('meta_description', 'Find answers to common questions about NumNam baby food, subscriptions, delivery, payments and more.')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">FAQ</span>
        <h1>Quick answers for parents</h1>
        <p>Everything from age suitability to subscriptions, payment, and delivery.</p>
    </div>
</section>

<section class="section fade-in-up">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion">
        @foreach($faqs as $i => $faq)
        <div class="accordion-item{{ $i === 0 ? ' open' : '' }}">
            <button type="button" class="accordion-trigger" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                {{ $faq['q'] }}
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>
            <div class="accordion-panel" style="{{ $i === 0 ? 'max-height:200px' : '' }}">
                <div class="accordion-panel-inner">{{ $faq['a'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="section fade-in-up faq-cta">
    <h3>Still have questions?</h3>
    <p class="meta faq-cta-text">Our care team is here to help you with anything.</p>
    <a class="btn btn-primary" href="{{ route('store.contact') }}">Contact Us</a>
</section>
@endsection