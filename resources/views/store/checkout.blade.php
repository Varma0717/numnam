@extends('store.layouts.app')

@section('title', 'NumNam - Checkout')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Checkout</span>
        <h1>Shipping, billing, and payment</h1>
        <p>Laravel order creation flow with order + order_items persistence.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section checkout-layout">
    <form method="POST" action="{{ route('store.checkout.place-order') }}" class="form-grid">
        @csrf
        <input class="input" name="ship_name" placeholder="Shipping full name" required>
        <input class="input" name="ship_phone" placeholder="Shipping phone" required>
        <input class="input" name="ship_address" placeholder="Address" required>
        <div class="store-grid two">
            <input class="input" name="ship_city" placeholder="City" required>
            <input class="input" name="ship_state" placeholder="State" required>
        </div>
        <input class="input" name="ship_pincode" placeholder="Pincode" required>

        <select class="input" name="payment_method" required>
            <option value="">Select payment method</option>
            <option value="razorpay">Razorpay (UPI / Cards)</option>
            <option value="stripe">Stripe (Cards / Wallets)</option>
            <option value="upi">Manual UPI</option>
            <option value="card">Manual Card</option>
            <option value="cod">Cash on Delivery</option>
            <option value="netbanking">Net Banking</option>
        </select>

        <input class="input" name="coupon_code" placeholder="Coupon code (optional)">
        @if(auth()->user()->referred_by)
            <p class="meta">Referral welcome discount is auto-applied on your first order.</p>
        @endif

        <textarea name="notes" placeholder="Order notes (optional)"></textarea>
        <button class="cta-btn" type="submit">Place order</button>
    </form>

    <aside class="summary-card">
        @foreach($items as $item)
            <div class="summary-row">
                <span>{{ $item['product']->name }} x {{ $item['qty'] }}</span>
                <strong>Rs {{ number_format($item['line_total'], 0) }}</strong>
            </div>
        @endforeach
        <div class="summary-row"><span>Shipping</span><strong>{{ $totals['shipping_fee'] > 0 ? 'Rs ' . number_format($totals['shipping_fee'], 0) : 'Free' }}</strong></div>
        <div class="summary-row total"><span>Total</span><strong>Rs {{ number_format($totals['total'], 0) }}</strong></div>
    </aside>
</section>
@endsection
