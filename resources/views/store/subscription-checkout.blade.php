@extends('store.layouts.app')

@section('title', 'NumNam - Subscription Checkout')
@section('meta_description', 'Complete your NumNam subscription setup. Confirm delivery details and start your subscription.')

@section('content')
<section class="section pb-4 pt-2">
    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500 sm:text-sm">
        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            Plan Selected
        </span>
        <span class="h-px w-8 bg-slate-300"></span>
        <span class="inline-flex items-center rounded-full border border-numnam-200 bg-numnam-50 px-3 py-1 text-numnam-700">2 Delivery Info</span>
        <span class="h-px w-8 bg-slate-300"></span>
        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1">3 Active</span>
    </div>
</section>

<section class="section pt-4">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <form method="POST" action="{{ route('store.subscription.checkout.place-order') }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
            @csrf
            <h2 class="text-xl font-semibold tracking-tight text-slate-900">Delivery Information</h2>
            <p class="mb-5 mt-2 text-sm text-slate-600">Your subscription will be delivered to this address starting from the next billing cycle.</p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="form-group">
                        <label for="ship_name" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_name" name="ship_name" placeholder="Full name" value="{{ old('ship_name', $user->name ?? '') }}" required>
                        @error('ship_name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="ship_phone" class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_phone" name="ship_phone" placeholder="Phone number" value="{{ old('ship_phone') }}" required pattern="[0-9]{10}" title="Enter 10-digit phone number">
                        @error('ship_phone') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 form-group">
                <label for="ship_address" class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_address" name="ship_address" placeholder="Street address" value="{{ old('ship_address') }}" required>
                @error('ship_address') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <div class="form-group">
                        <label for="ship_city" class="mb-1 block text-sm font-medium text-slate-700">City</label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_city" name="ship_city" placeholder="City" value="{{ old('ship_city') }}" required>
                        @error('ship_city') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="ship_state" class="mb-1 block text-sm font-medium text-slate-700">State</label>
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_state" name="ship_state" placeholder="State" value="{{ old('ship_state') }}">
                    </div>
                </div>
            </div>

            <div class="mt-4 form-group">
                <label for="ship_pincode" class="mb-1 block text-sm font-medium text-slate-700">Pincode</label>
                <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_pincode" name="ship_pincode" placeholder="Pincode" value="{{ old('ship_pincode') }}" pattern="[0-9]{6}" title="Enter 6-digit pincode">
            </div>

            <h2 class="mt-8 text-xl font-semibold tracking-tight text-slate-900">Subscription Confirmation</h2>
            <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm text-slate-700">
                    <strong>This subscription will:</strong>
                </p>
                <ul class="ml-5 mt-2 list-disc space-y-1 text-sm leading-relaxed text-slate-600">
                    <li>Start with your first delivery in the next billing cycle</li>
                    <li>Renew automatically every {{ $subscription['frequency'] === 'one_time' ? 'one time' : trim(strtolower(str_replace('_', ' ', $subscription['frequency']))) }}</li>
                    <li>Be delivered to the address above</li>
                    <li>Can be paused or cancelled anytime from your account</li>
                </ul>
            </div>

            <div class="mt-5 form-group">
                <label class="inline-flex items-start gap-2 text-sm text-slate-700">
                    <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-numnam-600 focus:ring-numnam-400" type="checkbox" name="terms_agreed" required>
                    <span>I agree to the <a href="{{ route('store.legal.terms') }}" target="_blank" class="font-semibold text-numnam-700 hover:text-numnam-600">terms and conditions</a> and <a href="{{ route('store.legal.privacy') }}" target="_blank" class="font-semibold text-numnam-700 hover:text-numnam-600">privacy policy</a></span>
                </label>
            </div>

            <button class="mt-7 inline-flex h-11 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="mr-1.5">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Activate Subscription
            </button>

            <a href="{{ route('store.pricing') }}" class="mt-3 inline-flex h-11 w-full items-center justify-center rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-50">
                Back to Plans
            </a>
        </form>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
            <h2 class="text-lg font-semibold text-slate-900">Subscription Summary</h2>

            <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                <span>Plan</span>
                <strong class="text-slate-900">{{ $subscription['plan_name'] }}</strong>
            </div>

            <div class="mt-2 flex items-center justify-between text-sm text-slate-600">
                <span>Billing Frequency</span>
                <strong class="text-slate-900">{{ $subscription['frequency'] === 'one_time' ? 'One Time' : ucfirst(str_replace('_', ' ', $subscription['frequency'])) }}</strong>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4">
                <span>Amount Per Cycle</span>
                <strong class="text-xl text-slate-900">Rs {{ number_format($subscription['price'], 0) }}</strong>
            </div>

            <div class="mt-6 space-y-2 border-t border-slate-200 pt-4">
                <p class="inline-flex items-center gap-1.5 text-sm text-slate-600">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    Your subscription is secure
                </p>
                <p class="inline-flex items-center gap-1.5 text-sm text-slate-600">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 20.5a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15Z" />
                    </svg>
                    Cancel anytime, no questions asked
                </p>
            </div>
        </aside>
    </div>
</section>
@endsection