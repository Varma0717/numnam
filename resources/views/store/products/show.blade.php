@extends('store.layouts.app')

@php
$productPlaceholders = [
asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'),
asset('assets/images/Purees/brocco%20pop%203.png'),
asset('assets/images/Purees/mangy%20chewy%203.png'),
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
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1.05fr_0.95fr]">
        {{-- Product Gallery --}}
        <div>
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <img src="{{ $mainPlaceholder }}" alt="{{ $product->name }}" loading="lazy">
            </div>
            @if($gallery->isNotEmpty())
            <div class="mt-4 grid grid-cols-4 gap-3">
                <div class="product-thumb active overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ $mainPlaceholder }}" alt="{{ $product->name }} main">
                </div>
                @foreach($gallery as $photo)
                <div class="product-thumb overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ $photo }}" alt="{{ $product->name }} gallery" loading="lazy">
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <span class="inline-flex rounded-full border border-numnam-200 bg-numnam-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $product->age_group }} | {{ ucfirst($product->type) }}</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $product->name }}</h1>

            @if($product->badges && count($product->badges))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($product->badges as $badge)
                <span class="inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-slate-600">{{ $badge }}</span>
                @endforeach
            </div>
            @endif

            <p class="mt-4 text-base leading-relaxed text-slate-600">{{ $product->short_description ?: $product->description }}</p>

            <div class="mt-5 flex flex-wrap items-center gap-3">
                <strong class="text-3xl font-bold text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                @if($product->sale_price)
                <del class="text-lg text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                <span class="inline-flex rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white">{{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF</span>
                @endif
            </div>

            @if($product->stock > 0)
            <p class="mt-3 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                In Stock {{ $product->stock < 10 ? '(' . $product->stock . ' left)' : '' }}
            </p>
            @else
            <p class="mt-3 inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-sm font-semibold text-rose-700">Out of Stock</p>
            @endif

            <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf
                <input class="h-11 w-24 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" type="number" min="1" name="qty" value="1" aria-label="Quantity">
                <button class="inline-flex h-11 items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit" {{ $product->stock <= 0 ? 'disabled' : '' }}>Add to Cart</button>
                <a class="inline-flex h-11 items-center justify-center rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-50" href="{{ route('store.checkout') }}">Buy Now</a>
            </form>

            <x-store.social-proof customers="10,000+" rating="4.8" />

            @auth
            <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}" class="mt-4">
                @csrf
                @php($isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists())
                <button type="submit" class="inline-flex h-11 items-center gap-2 rounded-full border px-5 text-sm font-semibold transition-colors duration-200 {{ $isWishlisted ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}" aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    {{ $isWishlisted ? 'In Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
            @endauth

            {{-- Trust signals --}}
            <div class="mt-6 space-y-2">
                <p class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                    Free shipping over Rs 500
                </p>
                <p class="inline-flex items-center gap-2 text-sm text-slate-600">
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
<section class="section fade-in-up">
    <nav class="product-tab-nav flex flex-wrap gap-2" role="tablist">
        <button class="product-tab active inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700" role="tab" aria-selected="true" aria-controls="tab-ingredients" data-tab="tab-ingredients">Ingredients</button>
        <button class="product-tab inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700" role="tab" aria-selected="false" aria-controls="tab-nutrition" data-tab="tab-nutrition">Nutrition Facts</button>
        <button class="product-tab inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700" role="tab" aria-selected="false" aria-controls="tab-story" data-tab="tab-story">Product Story</button>
    </nav>
    <div class="product-tab-panels mt-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="product-tab-panel active text-sm leading-relaxed text-slate-600" id="tab-ingredients" role="tabpanel">
            <p>{{ $product->ingredients ?: 'Ingredient details will be updated soon.' }}</p>
        </div>
        <div class="product-tab-panel text-sm leading-relaxed text-slate-600" id="tab-nutrition" role="tabpanel">
            @php($nutrition = $product->nutrition_facts ?: $product->nutrition_info)
            @if(is_array($nutrition) && !empty($nutrition))
            <ul class="nutrition-list">
                @foreach($nutrition as $key => $value)
                <li><span class="nutrition-key">{{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Nutrient' }}</span> <span class="nutrition-val">{{ is_scalar($value) ? $value : json_encode($value) }}</span></li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-slate-600">Detailed nutrition values are coming soon.</p>
            @endif
        </div>
        <div class="product-tab-panel text-sm leading-relaxed text-slate-600" id="tab-story" role="tabpanel">
            <p>{{ $product->description }}</p>
        </div>
    </div>
</section>

{{-- Customer Reviews Section --}}
<section class="section fade-in-up">
    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Customer Reviews</h2>
    @php($reviewCount = $product->approvedReviews()->count())
    @php($avgRating = $reviewCount > 0 ? round($product->approvedReviews()->avg('rating'), 1) : 0)
    <p class="mt-2 text-sm text-slate-600">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}@if($avgRating > 0) &middot; {{ $avgRating }} average @endif</p>

    @if($reviewCount > 0)
    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($product->approvedReviews()->with('user')->latest()->take(6)->get() as $review)
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div>
                <div class="rating" aria-label="{{ $review->rating }} out of 5 stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}">&#9733;</span>
                        @endfor
                </div>
                @if($review->title)<h4 class="review-title">{{ $review->title }}</h4>@endif
                <p>{{ $review->body }}</p>
                <p class="mt-3 text-sm text-slate-600"><strong>{{ $review->user->name }}</strong> &middot; {{ $review->created_at->diffForHumans() }}</p>
            </div>
        </article>
        @endforeach
    </div>
    @else
    <p class="mt-4 rounded-2xl border border-slate-200 bg-white p-4 text-sm text-slate-600">No reviews yet. Be the first to share your experience!</p>
    @endif

    {{-- Review Form --}}
    @auth
    <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
        <h4 class="text-lg font-semibold text-slate-900">Write a Review</h4>
        <form method="POST" action="{{ route('store.review.store', $product) }}" class="mt-4 space-y-4">
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
            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700">Submit Review</button>
        </form>
    </div>
    @else
    <p class="mt-4 text-sm text-slate-600"><a href="{{ route('store.login') }}" class="font-semibold text-numnam-700 hover:text-numnam-600">Log in</a> to write a review.</p>
    @endauth
</section>

@if($related->isNotEmpty())
<section class="section fade-in-up">
    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">You May Also Like</h2>
    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($related as $item)
        @php($relatedPlaceholder = $productPlaceholders[$loop->index % count($productPlaceholders)])
        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <div class="aspect-[4/3] bg-slate-100" style="background-image:url('{{ $relatedPlaceholder }}'); background-size:cover; background-position:center;"></div>
            <div class="p-5 sm:p-6">
                <h4 class="text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700"><a href="{{ route('store.product.show', $item) }}">{{ $item->name }}</a></h4>
                <div class="mt-3 flex items-center gap-2">
                    <strong class="text-lg text-slate-900">Rs {{ number_format($item->sale_price ?: $item->price, 0) }}</strong>
                    @if($item->sale_price)
                    <del class="text-sm text-slate-400">Rs {{ number_format($item->price, 0) }}</del>
                    @endif
                </div>
                <form method="POST" action="{{ route('store.cart.add', $item) }}" class="mt-4">
                    @csrf
                    <button class="inline-flex h-10 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">Add to Cart</button>
                </form>
                <x-store.social-proof customers="10,000+" rating="4.8" compact="true" />
            </div>
        </article>
        @endforeach
    </div>
</section>
@endif

@if(($recentlyViewedProducts ?? collect())->isNotEmpty())
<x-store.product-showcase
    title="Recently Viewed"
    subtitle="A quick way to revisit products you looked at recently."
    :products="$recentlyViewedProducts"
    empty-text="Your recently viewed products will appear here." />
@endif
@endsection