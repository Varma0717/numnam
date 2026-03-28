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
        "brand": {
            "@type": "Brand",
            "name": "NumNam"
        },
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
    @include('store.partials.breadcrumbs')
    <div class="product-detail-grid">
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
        <div class="product-info">
            <span class="kicker">{{ $product->age_group }} | {{ ucfirst($product->type) }}</span>
            <h1 class="product-title">{{ $product->name }}</h1>

            @if($product->badges && count($product->badges))
            <div class="chip-row">
                @foreach($product->badges as $badge)
                <span class="chip">{{ $badge }}</span>
                @endforeach
            </div>
            @endif

            <p class="product-description">{{ $product->short_description ?: $product->description }}</p>

            <div class="product-price">
                <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                @if($product->sale_price)
                <del>Rs {{ number_format($product->price, 0) }}</del>
                <span class="badge-sale">{{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF</span>
                @endif
            </div>

            @if($product->stock > 0)
            <p class="stock-status in-stock">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                In Stock {{ $product->stock < 10 ? '(' . $product->stock . ' left)' : '' }}
            </p>
            @else
            <p class="stock-status out-of-stock">Out of Stock</p>
            @endif

            <form method="POST" action="{{ route('store.cart.add', $product) }}" class="product-actions">
                @csrf
                <input class="input qty-input" type="number" min="1" name="qty" value="1" aria-label="Quantity">
                <button class="btn btn-primary" type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}>Add to Cart</button>
                <a class="btn btn-secondary" href="{{ route('store.checkout') }}">Buy Now</a>
            </form>

            @auth
            <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}" class="wishlist-action">
                @csrf
                @php($isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists())
                <button type="submit" class="btn-wishlist {{ $isWishlisted ? 'active' : '' }}" aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'var(--brand-3)' : 'none' }}" stroke="{{ $isWishlisted ? 'var(--brand-3)' : 'currentColor' }}" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    {{ $isWishlisted ? 'In Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
            @endauth

            {{-- Trust signals --}}
            <div class="product-trust-signals">
                <p class="meta trust-signal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                    Free shipping over Rs 500
                </p>
                <p class="meta trust-signal">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
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

{{-- Tabbed Content: Ingredients / Nutrition / Story --}}
<section class="section fade-in-up product-tabs-section">
    <nav class="product-tab-nav" role="tablist">
        <button class="product-tab active" role="tab" aria-selected="true" aria-controls="tab-ingredients" data-tab="tab-ingredients">Ingredients</button>
        <button class="product-tab" role="tab" aria-selected="false" aria-controls="tab-nutrition" data-tab="tab-nutrition">Nutrition Facts</button>
        <button class="product-tab" role="tab" aria-selected="false" aria-controls="tab-story" data-tab="tab-story">Product Story</button>
    </nav>
    <div class="product-tab-panels">
        <div class="product-tab-panel active" id="tab-ingredients" role="tabpanel">
            <p>{{ $product->ingredients ?: 'Ingredient details will be updated soon.' }}</p>
        </div>
        <div class="product-tab-panel" id="tab-nutrition" role="tabpanel">
            @php($nutrition = $product->nutrition_facts ?: $product->nutrition_info)
            @if(is_array($nutrition) && !empty($nutrition))
            <ul class="nutrition-list">
                @foreach($nutrition as $key => $value)
                <li><span class="nutrition-key">{{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Nutrient' }}</span> <span class="nutrition-val">{{ is_scalar($value) ? $value : json_encode($value) }}</span></li>
                @endforeach
            </ul>
            @else
            <p class="meta">Detailed nutrition values are coming soon.</p>
            @endif
        </div>
        <div class="product-tab-panel" id="tab-story" role="tabpanel">
            <p class="product-story">{{ $product->description }}</p>
        </div>
    </div>
</section>

{{-- Customer Reviews Section --}}
<section class="section fade-in-up">
    <h2>Customer Reviews</h2>
    @php($reviewCount = $product->approvedReviews()->count())
    @php($avgRating = $reviewCount > 0 ? round($product->approvedReviews()->avg('rating'), 1) : 0)
    <p class="meta">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}@if($avgRating > 0) &middot; {{ $avgRating }} average @endif</p>

    @if($reviewCount > 0)
    <div class="store-grid three">
        @foreach($product->approvedReviews()->with('user')->latest()->take(6)->get() as $review)
        <article class="card testimonial-card animate-fade-up" style="--stagger-delay: {{ $loop->index * 100 }}ms">
            <div class="card-body">
                <div class="rating" aria-label="{{ $review->rating }} out of 5 stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}">&#9733;</span>
                        @endfor
                </div>
                @if($review->title)<h4 class="review-title">{{ $review->title }}</h4>@endif
                <p>{{ $review->body }}</p>
                <p class="meta"><strong>{{ $review->user->name }}</strong> &middot; {{ $review->created_at->diffForHumans() }}</p>
            </div>
        </article>
        @endforeach
    </div>
    @else
    <p class="empty-state meta">No reviews yet. Be the first to share your experience!</p>
    @endif

    {{-- Review Form --}}
    @auth
    <div class="review-form-wrap animate-fade-up" style="margin-top:2rem">
        <h4>Write a Review</h4>
        <form method="POST" action="{{ route('store.review.store', $product) }}" class="review-form">
            @csrf
            <div class="form-group">
                <label>Rating</label>
                <div class="star-rating-input" role="radiogroup" aria-label="Rating">
                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                    <label for="star{{ $i }}" aria-label="{{ $i }} stars">&#9733;</label>
                    @endfor
                </div>
                @error('rating') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="review-title">Title (optional)</label>
                <input type="text" id="review-title" name="title" class="input" maxlength="150" value="{{ old('title') }}" placeholder="Sum it up in a few words">
            </div>
            <div class="form-group">
                <label for="review-body">Your Review</label>
                <textarea id="review-body" name="body" class="input" rows="4" required minlength="10" maxlength="2000" placeholder="Share your experience...">{{ old('body') }}</textarea>
                @error('body') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-primary">Submit Review</button>
        </form>
    </div>
    @else
    <p class="meta" style="margin-top:1rem"><a href="{{ route('store.login') }}">Log in</a> to write a review.</p>
    @endauth
</section>

@if($related->isNotEmpty())
<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>You May Also Like</h3>
        </div>
    </div>
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