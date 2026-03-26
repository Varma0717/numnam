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
        <div class="copy-box">
            <span class="meta">Your referral code:</span>
            <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                <strong class="referral-code-value" id="referral-code">{{ $referralCode ?: 'Will be generated soon' }}</strong>
                @if($referralCode)
                <button class="copy-box-btn" type="button" onclick="navigator.clipboard.writeText(document.getElementById('referral-code').textContent.trim()).then(()=>{this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)})">
                    Copy
                </button>
                @endif
            </div>
            @if($referralCode)
            <div class="share-buttons" style="margin-top:12px;">
                <a class="share-btn" href="https://wa.me/?text=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}%20to%20get%2015%25%20off!" target="_blank" rel="noopener" style="--share-clr:#25D366" aria-label="Share on WhatsApp">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.68-1.225A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.94 9.94 0 01-5.39-1.587l-.376-.232-3.277.858.874-3.188-.254-.404A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z" />
                    </svg>
                </a>
                <a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?quote=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}" target="_blank" rel="noopener" style="--share-clr:#1877F2" aria-label="Share on Facebook">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                </a>
                <a class="share-btn" href="https://twitter.com/intent/tweet?text=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}%20to%20get%2015%25%20off!" target="_blank" rel="noopener" style="--share-clr:#1DA1F2" aria-label="Share on Twitter">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                    </svg>
                </a>
                <a class="share-btn" href="mailto:?subject=NumNam%20Referral&body=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}%20to%20get%2015%25%20off!" style="--share-clr:#EA4335" aria-label="Share via Email">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                </a>
            </div>
            @endif
        </div>
        @endauth
    </div>
</section>

<section class="section animate-fade-up">
    <div class="section-head">
        <div>
            <h3>How It Works</h3>
        </div>
    </div>
    <div class="referral-steps stagger-children">
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

<section class="section animate-fade-up">
    <div class="store-grid two stagger-children">
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
<section class="section animate-fade-up" style="text-align:center;">
    <h3>Ready to Start Earning?</h3>
    <p class="meta" style="margin-bottom:16px;">Create an account to get your referral code and start sharing.</p>
    <a class="cta-btn" href="{{ route('store.register') }}">Create Account</a>
</section>
@endguest
@endsection