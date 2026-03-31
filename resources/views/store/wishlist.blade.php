@extends('store.layouts.app')
@section('title', 'My Wishlist — NumNam')

@section('content')
<section class="section-hero section-hero--mini fade-in-up">
    <h1>My Wishlist</h1>
</section>

@php
$productPlaceholders = [
asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'),
asset('assets/images/Purees/mangy%20chewy%201.png'),
asset('assets/images/Purees/brocco%20pop%201.png'),
];
@endphp

<section class="section fade-in-up">
    <div class="store-container">
        @if($products->count())
        <div class="store-grid three">
            @foreach($products as $product)
            @if($product)
            @php($placeholder = $productPlaceholders[$product->id % count($productPlaceholders)])
            <article class="card hover-up animate-fade-up" style="--stagger-delay: {{ $loop->index * 80 }}ms">
                <div class="media" style="background-image:url('{{ $placeholder }}'); background-size:cover;">
                    @if($product->sale_price)
                    <span class="lbl-hot">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                    @endif
                </div>
                <div class="card-body">
                    <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                    <div class="price">
                        <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                        @if($product->sale_price)
                        <del>Rs {{ number_format($product->price, 0) }}</del>
                        @endif
                    </div>
                    <div class="store-actions" style="display:flex; gap:8px; margin-top:12px;">
                        <form method="POST" action="{{ route('store.cart.add', $product) }}" style="flex:1">
                            @csrf
                            <button class="btn-primary" type="submit" style="width:100%">Add to Cart</button>
                        </form>
                        <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}">
                            @csrf
                            <button type="submit" class="btn-soft btn-wishlist active" aria-label="Remove from wishlist" style="padding:10px 14px">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="var(--brand-3)" stroke="var(--brand-3)" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <x-store.social-proof customers="10,000+" rating="4.8" compact="true" />
                </div>
            </article>
            @endif
            @endforeach
        </div>
        <div style="margin-top: 2rem;">{{ $products->links() }}</div>
        @else
        <div class="empty-state" style="text-align:center; padding: 4rem 1rem;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="1.5" style="margin-bottom:1rem">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
            <h3>Your wishlist is empty</h3>
            <p class="meta">Browse products and tap the heart to save them here.</p>
            <a href="{{ route('store.products') }}" class="btn-primary" style="margin-top:1rem; display:inline-block;">Browse Products</a>
        </div>
        @endif
    </div>
</section>
@endsection