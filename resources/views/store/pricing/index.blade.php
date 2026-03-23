@extends('store.layouts.app')

@section('title', 'NumNam - Subscriptions')

@section('content')
<section class="section fade-in-up" style="text-align:center;">
    <span class="kicker">Subscription Plans</span>
    <h1>Choose the right plan for your family</h1>
    <p class="meta" style="max-width:540px; margin:0 auto;">Save more with regular deliveries. Every plan includes free shipping and the flexibility to pause or cancel anytime.</p>
</section>

<section class="section fade-in-up">
    <div class="store-grid three">
        @forelse($plans as $index => $plan)
            <article class="card{{ $index === 1 ? ' card-popular' : '' }}">
                @if($index === 1)
                    <span class="popular-badge">Most Popular</span>
                @endif
                <div class="card-body" style="text-align:center;">
                    <h4>{{ $plan->name }}</h4>
                    <p class="meta">{{ $plan->description }}</p>
                    <div class="price" style="font-size:2rem; margin:16px 0;">
                        <strong>Rs {{ number_format($plan->price, 0) }}</strong>
                    </div>
                    <p class="meta" style="margin-bottom:16px;">{{ ucfirst(str_replace('_', ' ', $plan->billing_cycle)) }} &middot; {{ $plan->duration }}</p>
                    <div class="chip-row" style="justify-content:center; margin-bottom:16px;">
                        @foreach(($plan->features ?? []) as $feature)
                            <span class="chip">{{ $feature }}</span>
                        @endforeach
                    </div>
                    @auth
                        <form method="POST" action="{{ route('store.pricing.subscribe', $plan) }}">
                            @csrf
                            <button class="{{ $index === 1 ? 'cta-btn' : 'btn-primary' }}" type="submit" style="width:100%;">Subscribe Now</button>
                        </form>
                    @else
                        <a class="{{ $index === 1 ? 'cta-btn' : 'btn-primary' }}" style="display:block; text-align:center;" href="{{ route('store.login') }}">Login to Subscribe</a>
                    @endauth
                </div>
            </article>
        @empty
            <p class="meta">No active plans yet.</p>
        @endforelse
    </div>
</section>

<section class="section fade-in-up" style="text-align:center; max-width:600px; margin:0 auto;">
    <h3>Frequently Asked Questions</h3>
    <div class="accordion" style="text-align:left;">
        <div class="accordion-item">
            <button class="accordion-trigger">Can I cancel anytime?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>
            <div class="accordion-panel"><p>Yes! You can pause or cancel your subscription from your account dashboard at any time.</p></div>
        </div>
        <div class="accordion-item">
            <button class="accordion-trigger">When will my order be delivered?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>
            <div class="accordion-panel"><p>Subscription orders are dispatched at the start of each cycle. Delivery takes 3-5 business days.</p></div>
        </div>
        <div class="accordion-item">
            <button class="accordion-trigger">Can I switch plans?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>
            <div class="accordion-panel"><p>Absolutely. You can upgrade or downgrade at any time and the change will take effect on your next billing cycle.</p></div>
        </div>
    </div>
</section>
@endsection
