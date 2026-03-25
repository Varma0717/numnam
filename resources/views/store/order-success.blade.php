@extends('store.layouts.app')

@section('title', 'Order Confirmed - NumNam')

@section('content')
<section class="section">
    <div class="checkout-steps">
        <div class="checkout-step completed">Cart</div>
        <div class="checkout-step-line completed"></div>
        <div class="checkout-step completed">Checkout</div>
        <div class="checkout-step-line completed"></div>
        <div class="checkout-step active">Confirmation</div>
    </div>
</section>

<section class="section order-success fade-in-up">
    <div class="order-success-icon">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="20 6 9 17 4 12" />
        </svg>
    </div>
    <h1>Thank You for Your Order!</h1>
    <p class="order-number">Order #{{ $order->order_number }}</p>
    <p class="meta order-success-message">We've received your order and will begin processing it soon. You'll receive an email confirmation with tracking details.</p>

    <div class="order-success-actions">
        <a class="cta-btn" href="{{ route('store.account') }}">View My Orders</a>
        <a class="btn-soft" href="{{ route('store.products') }}">Continue Shopping</a>
    </div>
</section>

<section class="section fade-in-up order-details-section">
    <div class="summary-card">
        <h3 class="order-details-title">Order Details</h3>
        @foreach($order->items as $item)
        <div class="summary-row">
            <span>{{ $item->product_name }} <span class="meta">&times; {{ $item->quantity }}</span></span>
            <strong>Rs {{ number_format($item->line_total, 0) }}</strong>
        </div>
        @endforeach
        <div class="summary-row"><span>Subtotal</span><strong>Rs {{ number_format($order->subtotal, 0) }}</strong></div>
        @if($order->discount > 0)
        <div class="summary-row"><span>Discount</span><strong class="text-success">-Rs {{ number_format($order->discount, 0) }}</strong></div>
        @endif
        <div class="summary-row"><span>Shipping</span><strong>{{ $order->shipping_fee > 0 ? 'Rs ' . number_format($order->shipping_fee, 0) : 'Free' }}</strong></div>
        <div class="summary-row total"><span>Total</span><strong>Rs {{ number_format($order->total, 0) }}</strong></div>

        <div class="order-details-footer">
            <p class="meta"><strong>Payment:</strong> {{ strtoupper($order->payment_gateway ?: $order->payment_method ?? 'Pending') }}</p>
            <p class="meta"><strong>Shipping to:</strong> {{ $order->ship_name }}, {{ $order->ship_address }}, {{ $order->ship_city }} - {{ $order->ship_pincode }}</p>
        </div>
    </div>
</section>
@endsection