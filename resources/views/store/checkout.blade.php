@extends('store.layouts.app')

@section('title', 'NumNam - Checkout')
@section('meta_description', 'Complete your NumNam order. Secure checkout with Razorpay, Stripe, UPI and COD.')

@section('content')
<section class="section">
    {{-- Checkout Progress Steps --}}
    <div class="checkout-steps">
        <div class="checkout-step completed">Cart</div>
        <div class="checkout-step-line completed"></div>
        <div class="checkout-step active">Checkout</div>
        <div class="checkout-step-line"></div>
        <div class="checkout-step">Confirmation</div>
    </div>
</section>

<section class="section checkout-layout fade-in-up">
    <form method="POST" action="{{ route('store.checkout.place-order') }}" class="form-grid">
        @csrf
        <h3 style="margin:0;">Shipping Information</h3>
        <div class="store-grid two">
            <div class="form-group">
                <label for="ship_name">Full Name</label>
                <input class="input" id="ship_name" name="ship_name" placeholder="Full name" value="{{ old('ship_name', auth()->user()->name ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="ship_phone">Phone</label>
                <input class="input" id="ship_phone" name="ship_phone" placeholder="Phone number" value="{{ old('ship_phone') }}" required pattern="[0-9]{10}" title="Enter 10-digit phone number">
            </div>
        </div>
        <div class="form-group">
            <label for="ship_address">Address</label>
            <input class="input" id="ship_address" name="ship_address" placeholder="Street address" value="{{ old('ship_address') }}" required>
        </div>
        <div class="store-grid two">
            <div class="form-group">
                <label for="ship_city">City</label>
                <input class="input" id="ship_city" name="ship_city" placeholder="City" value="{{ old('ship_city') }}" required>
            </div>
            <div class="form-group">
                <label for="ship_state">State</label>
                <input class="input" id="ship_state" name="ship_state" placeholder="State" value="{{ old('ship_state') }}" required>
            </div>
        </div>
        <div class="form-group">
            <label for="ship_pincode">Pincode</label>
            <input class="input" id="ship_pincode" name="ship_pincode" placeholder="Pincode" value="{{ old('ship_pincode') }}" required pattern="[0-9]{6}" title="Enter 6-digit pincode" style="max-width:200px;">
        </div>

        <h3 style="margin:20px 0 0;">Payment Method</h3>
        <select class="input" name="payment_method" required>
            <option value="">Select payment method</option>
            <option value="razorpay" {{ old('payment_method') === 'razorpay' ? 'selected' : '' }}>Razorpay (UPI / Cards)</option>
            <option value="stripe" {{ old('payment_method') === 'stripe' ? 'selected' : '' }}>Stripe (Cards / Wallets)</option>
            <option value="upi" {{ old('payment_method') === 'upi' ? 'selected' : '' }}>Manual UPI</option>
            <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
            <option value="netbanking" {{ old('payment_method') === 'netbanking' ? 'selected' : '' }}>Net Banking</option>
        </select>

        <div class="store-grid two">
            <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input class="input" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" value="{{ old('coupon_code') }}">
            </div>
            <div class="form-group">
                @if(auth()->user()->referred_by)
                    <label>&nbsp;</label>
                    <p class="meta" style="padding-top:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2.5" style="vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg>
                        Referral welcome discount auto-applied
                    </p>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Order Notes (optional)</label>
            <textarea id="notes" name="notes" placeholder="Special instructions for delivery...">{{ old('notes') }}</textarea>
        </div>

        <button class="cta-btn" type="submit" style="font-size:14px; padding:14px 32px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle; margin-right:6px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            Place Secure Order
        </button>
    </form>

    <aside class="summary-card" style="position:sticky; top:90px;">
        <h3 style="margin:0 0 16px; font-size:18px;">Order Summary</h3>
        @foreach($items as $item)
            <div class="summary-row">
                <span>
                    {{ $item['product']->name }}
                    <span class="meta"> &times; {{ $item['qty'] }}</span>
                </span>
                <strong>Rs {{ number_format($item['line_total'], 0) }}</strong>
            </div>
        @endforeach
        <div class="summary-row"><span>Subtotal</span><strong>Rs {{ number_format($totals['subtotal'], 0) }}</strong></div>
        <div class="summary-row"><span>Shipping</span><strong>{{ $totals['shipping_fee'] > 0 ? 'Rs ' . number_format($totals['shipping_fee'], 0) : 'Free' }}</strong></div>
        <div class="summary-row total"><span>Total</span><strong>Rs {{ number_format($totals['total'], 0) }}</strong></div>

        <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--line);">
            <p class="meta" style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Secure SSL encrypted checkout
            </p>
            <p class="meta" style="display:flex;align-items:center;gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                7-day return policy
            </p>
        </div>
    </aside>
</section>
@endsection
