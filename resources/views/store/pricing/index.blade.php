@extends('store.layouts.app')

@section('title', 'NumNam - Subscriptions')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Subscription engine</span>
        <h1>Choose your subscription plan</h1>
        <p>Create recurring subscription records directly in Laravel and manage them from account and admin APIs.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="store-grid three">
        @forelse($plans as $plan)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $plan->name }}</h4>
                    <p class="meta">{{ $plan->description }}</p>
                    <div class="price"><strong>Rs {{ number_format($plan->price, 0) }}</strong></div>
                    <p class="meta">{{ ucfirst(str_replace('_', ' ', $plan->billing_cycle)) }} | {{ $plan->duration }}</p>
                    <div class="chip-row">
                        @foreach(($plan->features ?? []) as $feature)
                            <span class="chip">{{ $feature }}</span>
                        @endforeach
                    </div>
                    @auth
                        <form method="POST" action="{{ route('store.pricing.subscribe', $plan) }}" style="margin-top:.6rem;">
                            @csrf
                            <button class="btn-primary" type="submit">Subscribe</button>
                        </form>
                    @else
                        <a class="btn-primary" style="display:inline-block; margin-top:.6rem;" href="{{ route('store.login') }}">Login to Subscribe</a>
                    @endauth
                </div>
            </article>
        @empty
            <p class="meta">No active plans yet.</p>
        @endforelse
    </div>
</section>
@endsection
