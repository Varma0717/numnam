@extends('store.layouts.app')

@section('title', 'NumNam - Refer Friends')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Refer Friends</span>
        <h1>Give 15%, get 15%</h1>
        <p>Invite your friends to NumNam and earn referral rewards as they place their first order.</p>
        @auth
            <p class="meta" style="margin-top:.7rem;">Your referral code: <strong>{{ $referralCode ?: 'Will be generated soon' }}</strong></p>
        @endauth
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="store-grid two">
        <article class="card">
            <div class="card-body">
                <h4>Step 1: Share your code</h4>
                <p class="meta">Send your referral link to friends and family from your account dashboard.</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>Step 2: They save on first order</h4>
                <p class="meta">New customers get a first-order discount when they register using your code.</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>Step 3: You earn rewards</h4>
                <p class="meta">Referral credits are added to your reward wallet after qualifying orders.</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>Track everything</h4>
                <p class="meta">View referred users and reward ledger history in the My Account page.</p>
            </div>
        </article>
    </div>
</section>
@endsection
