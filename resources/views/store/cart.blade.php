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
    <div class="hero-art"></div>
</section>

<section class="section">
    @if(empty($items))
        <p class="meta">Your cart is empty. <a href="{{ route('store.products') }}">Browse products</a>.</p>
    @else
        <div class="store-grid">
            @foreach($items as $item)
                @php
                    $placeholderImage = $productPlaceholders[$item['product']->id % count($productPlaceholders)];
                    $lineImage = $item['product']->image ?: $placeholderImage;
                @endphp
                <article class="line-item">
                    <div class="line-media" style="background-image:url('{{ $lineImage }}')"></div>
                    <div>
                        <h4>{{ $item['product']->name }}</h4>
                        <p class="meta">Rs {{ number_format($item['unit_price'], 0) }} each</p>
                        <div class="store-actions">
                            <form method="POST" action="{{ route('store.cart.update', $item['product']) }}">
                                @csrf
                                <input class="input" style="max-width:90px;" type="number" min="1" name="qty" value="{{ $item['qty'] }}">
                                <button class="btn-ghost" type="submit">Update</button>
                            </form>
                            <form method="POST" action="{{ route('store.cart.remove', $item['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn-ghost" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach

            <aside class="summary-card">
                <div class="summary-row"><span>Subtotal</span><strong>Rs {{ number_format($totals['subtotal'], 0) }}</strong></div>
                <div class="summary-row"><span>Shipping</span><strong>{{ $totals['shipping_fee'] > 0 ? 'Rs ' . number_format($totals['shipping_fee'], 0) : 'Free' }}</strong></div>
                <div class="summary-row total"><span>Total</span><strong>Rs {{ number_format($totals['total'], 0) }}</strong></div>
                <a class="cta-btn" href="{{ route('store.checkout') }}">Proceed to Checkout</a>
            </aside>
        </div>
    @endif
</section>
@endsection
