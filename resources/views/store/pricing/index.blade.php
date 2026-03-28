@extends('store.layouts.app')

@section('title', 'NumNam - Subscriptions')

@section('content')
<section class="section animate-fade-up pricing-hero">
    <span class="kicker">Subscription Plans</span>
    <h1>Choose the right plan for your family</h1>
    <p class="meta pricing-subtitle">Save more with regular deliveries. Every plan includes free shipping and the flexibility to pause or cancel anytime.</p>
</section>

<section class="section animate-fade-up">
    <div class="store-grid three stagger-children">
        @forelse($plans as $plan)
        @php($isBestValue = collect($plan->features ?? [])->contains(fn($feature) => strtolower((string) $feature) === 'best value'))
        @php($cycleLabel = $plan->billing_cycle === 'one_time' ? 'One time' : 'Every ' . strtolower(str_replace('_', ' ', $plan->billing_cycle)))
        <article class="card pricing-card{{ $isBestValue ? ' card-popular glow-pulse' : '' }}">
            @if($isBestValue)
            <span class="popular-badge">Best Value</span>
            @endif
            <div class="card-body pricing-card-body">
                <h4>{{ $plan->name }}</h4>
                <p class="meta">{{ $plan->description }}</p>
                <div class="pricing-amount">
                    <strong>Rs {{ number_format($plan->price, 0) }}</strong>
                </div>
                <p class="meta pricing-cycle">{{ $cycleLabel }}</p>
                <p class="meta pricing-cycle">{{ $plan->duration }}</p>
                <div class="chip-row pricing-features">
                    @foreach(($plan->features ?? []) as $feature)
                    <span class="chip">{{ $feature }}</span>
                    @endforeach
                </div>
                @auth
                <form method="POST" action="{{ route('store.pricing.subscribe', $plan) }}">
                    @csrf
                    <button class="{{ $isBestValue ? 'cta-btn' : 'btn-primary' }} pricing-subscribe-btn" type="submit">Select</button>
                </form>
                @else
                <a class="{{ $isBestValue ? 'cta-btn' : 'btn-primary' }} pricing-subscribe-btn" href="{{ route('store.login') }}">Select</a>
                @endauth
            </div>
        </article>
        @empty
        <p class="meta">No active plans yet.</p>
        @endforelse
    </div>
</section>

<section class="section animate-fade-up pricing-faq">
    <h3>Frequently Asked Questions</h3>
    <div class="accordion pricing-accordion">
        <div class="accordion-item">
            <button class="accordion-trigger">Can I cancel anytime?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9" />
                </svg></button>
            <div class="accordion-panel">
                <p>Yes! You can pause or cancel your subscription from your account dashboard at any time.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-trigger">When will my order be delivered?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9" />
                </svg></button>
            <div class="accordion-panel">
                <p>Subscription orders are dispatched at the start of each cycle. Delivery takes 3-5 business days.</p>
            </div>
        </div>
        <div class="accordion-item">
            <button class="accordion-trigger">Can I switch plans?<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9" />
                </svg></button>
            <div class="accordion-panel">
                <p>Absolutely. You can upgrade or downgrade at any time and the change will take effect on your next billing cycle.</p>
            </div>
        </div>
    </div>
</section>
@endsection