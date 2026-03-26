@extends('store.layouts.app')

@section('title', 'NumNam - Cart')

@section('content')
@php
$productPlaceholders = [
asset('assets/images/product_1.png'),
asset('assets/images/product_2.png'),
asset('assets/images/product_3.png'),
asset('assets/images/product_4.png'),
];
@endphp

<section class="hero section in-view">
    <div>
        <span class="kicker">Cart</span>
        <h1>Your selected products</h1>
        <p>Update quantity or proceed to checkout.</p>
    </div>
</section>

<section class="section">
    @if(empty($items))
    <div class="empty-state animate-fade-up">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="var(--line)" stroke-width="1.2" style="margin: 0 auto 20px;">
            <circle cx="9" cy="21" r="1" />
            <circle cx="20" cy="21" r="1" />
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
        </svg>
        <h3>Your cart is empty</h3>
        <p class="meta" style="margin-bottom:16px;">Looks like you haven't added anything yet.</p>
        <a class="cta-btn" href="{{ route('store.products') }}">Browse Products</a>
    </div>
    @else
    <div class="cart-layout animate-fade-up">
        <div class="cart-items">
            @foreach($items as $item)
            @php
            $placeholderImage = $productPlaceholders[$item['product']->id % count($productPlaceholders)];
            $lineImage = $item['product']->image ?: $placeholderImage;
            @endphp
            <article class="line-item">
                <div class="line-media" style="background-image:url('{{ $lineImage }}')"></div>
                <div class="line-details">
                    <h4><a href="{{ route('store.product.show', $item['product']) }}">{{ $item['product']->name }}</a></h4>
                    <p class="meta">Rs {{ number_format($item['unit_price'], 0) }} each</p>
                    <div class="line-actions">
                        <form method="POST" action="{{ route('store.cart.update', $item['product']) }}" class="qty-form">
                            @csrf
                            <div class="qty-stepper">
                                <button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown();this.form.submit()" aria-label="Decrease quantity">&minus;</button>
                                <input class="input qty-input" type="number" min="1" name="qty" value="{{ $item['qty'] }}" onchange="this.form.submit()">
                                <button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp();this.form.submit()" aria-label="Increase quantity">&plus;</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('store.cart.remove', $item['product']) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-remove" type="submit" aria-label="Remove {{ $item['product']->name }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                </svg>
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
                <div class="line-total">
                    <strong>Rs {{ number_format($item['unit_price'] * $item['qty'], 0) }}</strong>
                </div>
            </article>
            @endforeach
        </div>

        <aside class="summary-card checkout-summary">
            <h3 class="order-details-title" style="font-size:18px; margin:0 0 16px;">Order Summary</h3>
            <div class="summary-row"><span>Subtotal ({{ count($items) }} item{{ count($items) > 1 ? 's' : '' }})</span><strong>Rs {{ number_format($totals['subtotal'], 0) }}</strong></div>
            <div class="summary-row"><span>Shipping</span><strong>{{ $totals['shipping_fee'] > 0 ? 'Rs ' . number_format($totals['shipping_fee'], 0) : 'Free' }}</strong></div>
            @if($totals['shipping_fee'] == 0)
            <p class="meta" style="font-size:12px; margin:0;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2.5" style="vertical-align:middle;">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Free shipping on this order!
            </p>
            @endif
            <div class="summary-row total"><span>Total</span><strong>Rs {{ number_format($totals['total'], 0) }}</strong></div>
            <a class="cta-btn" href="{{ route('store.checkout') }}" style="display:block;text-align:center;margin-top:12px;">Proceed to Checkout</a>
            <a href="{{ route('store.products') }}" class="meta" style="display:block;text-align:center;margin-top:10px;">Continue Shopping &rarr;</a>
        </aside>
    </div>
    @endif
</section>
@endsection