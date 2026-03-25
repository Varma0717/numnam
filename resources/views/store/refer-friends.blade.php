@extends('store.layouts.app')

@section('title', 'NumNam - Refer Friends')
@section('meta_description', 'Invite friends to NumNam and earn referral rewards when they place their first order.')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Refer Friends</span>
        <h1>Give 15%, get 15%</h1>
        <p>Invite your friends to NumNam and earn referral rewards as they place their first order.</p>
        @auth
        <div class="referral-code-display">
            <span class="meta">Your referral code:</span>
            <strong class="referral-code-value">{{ $referralCode ?: 'Will be generated soon' }}</strong>
        </div>
        @endauth
    </div>
    <div class="hero-art"></div>
</section>

<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>How It Works</h3>
        </div>
    </div>
    <div class="referral-steps">
        <article class="referral-step-card">
            <div class="referral-step-number">1</div>
            <h4>Share Your Code</h4>
            <p class="meta">Send your referral link to friends and family from your account dashboard.</p>
        </article>
        <div class="referral-step-connector">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </div>
        <article class="referral-step-card">
            <div class="referral-step-number">2</div>
            <h4>They Save on First Order</h4>
            <p class="meta">New customers get a first-order discount when they register using your code.</p>
        </article>
        <div class="referral-step-connector">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </div>
        <article class="referral-step-card">
            <div class="referral-step-number">3</div>
            <h4>You Earn Rewards</h4>
            <p class="meta">Referral credits are added to your reward wallet after qualifying orders.</p>
        </article>
    </div>
</section>

<section class="section fade-in-up">
    <div class="store-grid two">
        <article class="card">
            <div class="card-body">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2" style="vertical-align:middle; margin-right:6px;">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                        <polyline points="17 6 23 6 23 12" />
                    </svg>
                    Track Everything
                </h4>
                <p class="meta">View referred users and reward ledger history in the My Account page. See who signed up, who placed orders, and how much you've earned.</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2" style="vertical-align:middle; margin-right:6px;">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" />
                        <line x1="15" y1="9" x2="15.01" y2="9" />
                    </svg>
                    No Limits
                </h4>
                <p class="meta">There's no cap on how many friends you can refer. Every qualifying order earns you credits toward your next purchase.</p>
            </div>
        </article>
    </div>
</section>

@guest
<section class="section fade-in-up" style="text-align:center;">
    <h3>Ready to Start Earning?</h3>
    <p class="meta" style="margin-bottom:16px;">Create an account to get your referral code and start sharing.</p>
    <a class="cta-btn" href="{{ route('store.register') }}">Create Account</a>
</section>
@endguest
@endsection