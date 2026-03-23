@extends('store.layouts.app')

@php
    $productPlaceholders = [
        asset('assets/images/product_1.png'),
        asset('assets/images/product_2.png'),
        asset('assets/images/product_3.png'),
        asset('assets/images/product_4.png'),
    ];
    $mainPlaceholder = $productPlaceholders[$product->id % count($productPlaceholders)];
@endphp

@section('title', 'NumNam - ' . $product->name)
@section('meta_description', Str::limit($product->short_description ?: $product->description, 160))
@section('og_image', $mainPlaceholder)

@section('structured_data')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ Str::limit($product->description, 300) }}",
    "image": "{{ $mainPlaceholder }}",
    "brand": {"@type": "Brand", "name": "NumNam"},
    "offers": {
        "@type": "Offer",
        "price": "{{ $product->sale_price ?: $product->price }}",
        "priceCurrency": "INR",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    }
}
</script>
@endsection

@section('content')
<section class="section fade-in-up">
    <div class="store-grid two" style="align-items:start;">
        {{-- Product Gallery --}}
        <div class="product-gallery">
            <div class="product-gallery-main">
                <img src="{{ $mainPlaceholder }}" alt="{{ $product->name }}" loading="lazy">
            </div>
            @if($gallery->isNotEmpty())
            <div class="product-gallery-thumbs">
                <div class="product-thumb active">
                    <img src="{{ $mainPlaceholder }}" alt="{{ $product->name }} main">
                </div>
                @foreach($gallery as $photo)
                    <div class="product-thumb">
                        <img src="{{ $photo }}" alt="{{ $product->name }} gallery" loading="lazy">
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <span class="kicker">{{ $product->age_group }} | {{ ucfirst($product->type) }}</span>
            <h1 style="font-family:'DM Sans',sans-serif; font-size:28px; margin:12px 0 8px;">{{ $product->name }}</h1>

            @if($product->badges && count($product->badges))
                <div class="chip-row" style="margin-bottom:12px;">
                    @foreach($product->badges as $badge)
                        <span class="chip">{{ $badge }}</span>
                    @endforeach
                </div>
            @endif

            <p class="meta" style="font-size:16px; line-height:26px;">{{ $product->short_description ?: $product->description }}</p>

            <div class="price" style="font-size:24px; margin:16px 0;">
                <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                @if($product->sale_price)
                    <del>Rs {{ number_format($product->price, 0) }}</del>
                    <span class="badge-sale" style="font-size:12px;">{{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF</span>
                @endif
            </div>

            @if($product->stock > 0)
                <p class="meta" style="color:#00a32a; font-weight:600;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2.5" style="vertical-align:middle;"><polyline points="20 6 9 17 4 12"/></svg>
                    In Stock {{ $product->stock < 10 ? '(' . $product->stock . ' left)' : '' }}
                </p>
            @else
                <p class="meta" style="color:var(--danger); font-weight:600;">Out of Stock</p>
            @endif

            <form method="POST" action="{{ route('store.cart.add', $product) }}" class="store-actions" style="margin-top:20px;">
                @csrf
                <input class="input" style="max-width:100px;" type="number" min="1" name="qty" value="1">
                <button class="cta-btn" type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}>Add to Cart</button>
                <a class="btn-soft" href="{{ route('store.checkout') }}">Buy Now</a>
            </form>

            {{-- Trust signals --}}
            <div style="display:flex; gap:20px; margin-top:24px; flex-wrap:wrap;">
                <p class="meta" style="display:flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Free shipping over Rs 500
                </p>
                <p class="meta" style="display:flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    100% Clean Label
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div class="lightbox" id="productLightbox" hidden>
    <button type="button" class="lightbox-close" aria-label="Close">&times;</button>
    <img src="" alt="Product zoom">
</div>

<section class="section fade-in-up">
    <div class="section-head"><div><h3>Ingredients & Nutrition</h3></div></div>
    <div class="store-grid two">
        <article class="card">
            <div class="card-body">
                <h4>Ingredients</h4>
                <p class="meta">{{ $product->ingredients ?: 'Ingredient details will be updated soon.' }}</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>Nutrition Facts</h4>
                @php($nutrition = $product->nutrition_facts ?: $product->nutrition_info)
                @if(is_array($nutrition) && !empty($nutrition))
                    <ul>
                        @foreach($nutrition as $key => $value)
                            <li>{{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Nutrient' }}: {{ is_scalar($value) ? $value : json_encode($value) }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="meta">Detailed nutrition values are coming soon.</p>
                @endif
            </div>
        </article>
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head"><div><h3>Product Story</h3></div></div>
    <p class="meta" style="font-size:16px; line-height:28px; max-width:80ch;">{{ $product->description }}</p>
</section>

{{-- Customer Reviews Section --}}
<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>Customer Reviews</h3>
            <p class="section-sub">What parents are saying</p>
        </div>
    </div>
    <div class="store-grid three">
        <article class="card testimonial-card">
            <div class="card-body">
                <div class="rating" style="justify-content:center;margin-bottom:10px;">
                    @for($i=0;$i<5;$i++)<span style="color:var(--brand-2);font-size:16px;">&#9733;</span>@endfor
                </div>
                <p>"My baby loves this! The texture is perfect for her age and I trust the clean ingredients."</p>
                <p class="meta"><strong>Priya M.</strong> &middot; Verified Buyer</p>
            </div>
        </article>
        <article class="card testimonial-card">
            <div class="card-body">
                <div class="rating" style="justify-content:center;margin-bottom:10px;">
                    @for($i=0;$i<5;$i++)<span style="color:var(--brand-2);font-size:16px;">&#9733;</span>@endfor
                </div>
                <p>"Finally a baby food brand that's transparent about what goes in. Highly recommend!"</p>
                <p class="meta"><strong>Rahul K.</strong> &middot; Verified Buyer</p>
            </div>
        </article>
        <article class="card testimonial-card">
            <div class="card-body">
                <div class="rating" style="justify-content:center;margin-bottom:10px;">
                    @for($i=0;$i<4;$i++)<span style="color:var(--brand-2);font-size:16px;">&#9733;</span>@endfor
                    <span style="color:var(--line);font-size:16px;">&#9733;</span>
                </div>
                <p>"Great quality and fast delivery. My baby finished the whole pack in a week!"</p>
                <p class="meta"><strong>Sneha R.</strong> &middot; Verified Buyer</p>
            </div>
        </article>
    </div>
</section>

@if($related->isNotEmpty())
<section class="section fade-in-up">
    <div class="section-head"><div><h3>You May Also Like</h3></div></div>
    <div class="store-grid three">
        @foreach($related as $item)
            @php($relatedPlaceholder = $productPlaceholders[$loop->index % count($productPlaceholders)])
            <article class="card hover-up">
                <div class="media" style="background-image:url('{{ $relatedPlaceholder }}'); background-size:cover;"></div>
                <div class="card-body">
                    <h4><a href="{{ route('store.product.show', $item) }}">{{ $item->name }}</a></h4>
                    <div class="price">
                        <strong>Rs {{ number_format($item->sale_price ?: $item->price, 0) }}</strong>
                        @if($item->sale_price)
                            <del>Rs {{ number_format($item->price, 0) }}</del>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.cart.add', $item) }}" class="store-actions">
                        @csrf
                        <button class="btn-primary" type="submit">Add to Cart</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection
