@extends('store.layouts.app')

@section('title', 'NumNam - Checkout')
@section('meta_description', 'Complete your NumNam order. Secure checkout with Razorpay, Stripe, UPI and COD.')

@section('content')
<section class="section">
    {{-- Checkout Progress Steps --}}
    <div class="progress-steps">
        <div class="progress-step completed">
            <span class="step-num">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </span>
            <span>Cart</span>
        </div>
        <div class="progress-step-line filled"></div>
        <div class="progress-step active">
            <span class="step-num">2</span>
            <span>Checkout</span>
        </div>
        <div class="progress-step-line"></div>
        <div class="progress-step">
            <span class="step-num">3</span>
            <span>Confirmation</span>
        </div>
    </div>
</section>

<section class="section checkout-layout animate-fade-up">
    <form method="POST" action="{{ route('store.checkout.place-order') }}" class="form-grid">
        @csrf
        <h3 class="checkout-section-title">Shipping Information</h3>
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
            <input class="input pincode-input" id="ship_pincode" name="ship_pincode" placeholder="Pincode" value="{{ old('ship_pincode') }}" required pattern="[0-9]{6}" title="Enter 6-digit pincode">
        </div>

        <h3 class="checkout-section-title checkout-payment-title">Payment Method</h3>
        <div class="payment-methods">
            @php
            $methods = [
            ['value' => 'razorpay', 'label' => 'Razorpay', 'desc' => 'UPI, Cards, Wallets', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" />
                <line x1="1" y1="10" x2="23" y2="10" />
            </svg>'],
            ['value' => 'stripe', 'label' => 'Stripe', 'desc' => 'International Cards', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" />
                <line x1="1" y1="10" x2="23" y2="10" />
            </svg>'],
            ['value' => 'upi', 'label' => 'Manual UPI', 'desc' => 'Pay via UPI ID', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>'],
            ['value' => 'cod', 'label' => 'Cash on Delivery', 'desc' => 'Pay when delivered', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="6" width="20" height="12" rx="2" />
                <circle cx="12" cy="12" r="3" />
            </svg>'],
            ['value' => 'netbanking', 'label' => 'Net Banking', 'desc' => 'Bank transfer', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 14v3M12 14v3M16 14v3" />
            </svg>'],
            ];
            @endphp
            @foreach($methods as $method)
            <label class="payment-card {{ old('payment_method') === $method['value'] ? 'selected' : '' }}">
                <input type="radio" name="payment_method" value="{{ $method['value'] }}" {{ old('payment_method') === $method['value'] ? 'checked' : '' }} required>
                <span class="payment-card-icon">{!! $method['icon'] !!}</span>
                <span class="payment-card-text">
                    <strong>{{ $method['label'] }}</strong>
                    <span class="meta">{{ $method['desc'] }}</span>
                </span>
            </label>
            @endforeach
        </div>

        <div class="store-grid two">
            <div class="form-group">
                <label for="coupon_code">Coupon Code</label>
                <input class="input" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" value="{{ old('coupon_code') }}">
            </div>
            <div class="form-group">
                @if(auth()->check() && auth()->user()->referred_by)
                <label>&nbsp;</label>
                <p class="meta checkout-referral-note">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2.5" style="vertical-align:middle;">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Referral welcome discount auto-applied
                </p>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Order Notes (optional)</label>
            <textarea id="notes" name="notes" placeholder="Special instructions for delivery...">{{ old('notes') }}</textarea>
        </div>

        <button class="cta-btn checkout-submit-btn" type="submit">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle; margin-right:6px;">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                <path d="M7 11V7a5 5 0 0110 0v4" />
            </svg>
            Place Secure Order
        </button>
    </form>

    <aside class="summary-card checkout-summary">
        <h3 class="order-details-title">Order Summary</h3>
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

        <div class="order-details-footer">
            <p class="meta checkout-trust-line">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Secure SSL encrypted checkout
            </p>
            <p class="meta checkout-trust-line">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                    <polyline points="17 6 23 6 23 12" />
                </svg>
                7-day return policy
            </p>
        </div>
    </aside>
</section>
@endsection