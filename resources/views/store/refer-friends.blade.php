@extends('store.layouts.app')

@section('title', 'NumNam - Refer Friends')
@section('meta_description', 'Invite friends to NumNam and earn referral rewards when they place their first order.')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fffaf4] via-white to-[#fff3e6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-orange-100/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Refer Friends</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Give 15%, get 15%</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Invite your friends to NumNam and earn referral rewards as they place their first order.</p>
            @auth
            <div class="mt-6 inline-flex flex-col gap-3 rounded-2xl border border-numnam-200 bg-white/90 px-5 py-4 shadow-sm sm:flex-row sm:items-center">
                <div>
                    <p class="text-xs font-medium text-slate-500">Your referral code</p>
                    <strong class="text-lg font-mono font-bold text-slate-900" id="referral-code">{{ $referralCode ?: 'Will be generated soon' }}</strong>
                </div>
                @if($referralCode)
                <button class="rounded-xl border border-numnam-200 bg-numnam-50 px-4 py-2 text-sm font-semibold text-numnam-700 transition hover:bg-numnam-100" type="button" onclick="navigator.clipboard.writeText(document.getElementById('referral-code').textContent.trim()).then(()=>{this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)})">Copy</button>
                @endif
            </div>
            @if($referralCode)
            <div class="mt-4 flex flex-wrap gap-2">
                <a class="inline-flex h-9 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    href="https://wa.me/?text=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}%20to%20get%2015%25%20off!" target="_blank" rel="noopener" aria-label="Share on WhatsApp">
                    <svg class="h-4 w-4 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.612.638l4.68-1.225A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22a9.94 9.94 0 01-5.39-1.587l-.376-.232-3.277.858.874-3.188-.254-.404A9.935 9.935 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z" />
                    </svg>
                    WhatsApp
                </a>
                <a class="inline-flex h-9 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    href="https://www.facebook.com/sharer/sharer.php?quote=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}" target="_blank" rel="noopener" aria-label="Share on Facebook">
                    <svg class="h-4 w-4 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                    Facebook
                </a>
                <a class="inline-flex h-9 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                    href="mailto:?subject=NumNam%20Referral&body=Use%20my%20NumNam%20referral%20code%20{{ $referralCode }}%20to%20get%2015%25%20off!" aria-label="Share via Email">
                    <svg class="h-4 w-4 text-[#EA4335]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    Email
                </a>
            </div>
            @endif
            @endauth
        </div>
    </div>
</section>

<section class="section pb-8">
    <h2 class="mb-6 text-xl font-extrabold text-slate-900">How It Works</h2>
    <div class="flex flex-col items-stretch gap-4 md:flex-row md:items-start">
        <article class="flex-1 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-numnam-100 text-lg font-extrabold text-numnam-700">1</span>
            <h4 class="mt-3 font-bold text-slate-900">Share Your Code</h4>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">Send your referral link to friends and family from your account dashboard.</p>
        </article>
        <div class="hidden items-center justify-center md:flex">
            <svg class="h-6 w-6 text-numnam-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </div>
        <article class="flex-1 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-numnam-100 text-lg font-extrabold text-numnam-700">2</span>
            <h4 class="mt-3 font-bold text-slate-900">They Save on First Order</h4>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">New customers get a first-order discount when they register using your code.</p>
        </article>
        <div class="hidden items-center justify-center md:flex">
            <svg class="h-6 w-6 text-numnam-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </div>
        <article class="flex-1 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-numnam-100 text-lg font-extrabold text-numnam-700">3</span>
            <h4 class="mt-3 font-bold text-slate-900">You Earn Rewards</h4>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">Referral credits are added to your reward wallet after qualifying orders.</p>
        </article>
    </div>
</section>

<section class="section pb-8">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-2 text-numnam-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                    <polyline points="17 6 23 6 23 12" />
                </svg>
                <h4 class="font-bold text-slate-900">Track Everything</h4>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-slate-500">View referred users and reward ledger history in the My Account page. See who signed up, who placed orders, and how much you've earned.</p>
        </article>
        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center gap-2 text-numnam-700">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                    <line x1="9" y1="9" x2="9.01" y2="9" />
                    <line x1="15" y1="9" x2="15.01" y2="9" />
                </svg>
                <h4 class="font-bold text-slate-900">No Limits</h4>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-slate-500">There's no cap on how many friends you can refer. Every qualifying order earns you credits toward your next purchase.</p>
        </article>
    </div>
</section>

@guest
<section class="section pb-14">
    <div class="rounded-3xl border border-numnam-100 bg-gradient-to-br from-numnam-50 to-white px-8 py-10 text-center shadow-sm">
        <h3 class="text-xl font-extrabold text-slate-900">Ready to Start Earning?</h3>
        <p class="mt-2 text-sm text-slate-500">Create an account to get your referral code and start sharing.</p>
        <a class="mt-5 inline-flex h-11 items-center rounded-full bg-numnam-600 px-8 text-sm font-semibold text-white transition hover:bg-numnam-700" href="{{ route('store.register') }}">Create Account</a>
    </div>
</section>
@endguest
@endsection