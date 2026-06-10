@extends('store.layouts.app')

@php
// Use product image_url as primary source, fallback to gallery first item, no placeholders
$mainImage = $product->image_url ?: ($gallery->isNotEmpty() ? $gallery->first() : null);

$recentlyViewed = $recentlyViewedProducts ?? collect();
$reviews = $reviews ?? collect();
$reviewCount = (int) ($product->approved_reviews_count ?? $reviews->count());
$avgRating = $reviewCount > 0 ? round((float) ($product->approved_reviews_avg_rating ?? 0), 1) : 0;
$nutrition = $product->nutrition_facts ?: $product->nutrition_info;
$productType = $product->type;

$ingredientBadges = $productType === 'puree'
? ['No Preservatives', 'No Added Sugar', 'No Added Salt', 'Vegetable-Forward']
: ['No Preservatives', 'No Additives', 'No MSG', 'Pea Protein', 'Super Grain Blend'];

$benefits = $productType === 'puree'
? [
['Vegetable-Forward', '30-40% vegetable content in selected variants introduces kids to healthy flavours early.'],
['Easy Early Feeding', 'Smooth texture designed for babies from 6 months beginning their food journey.'],
['Home or Travel', 'Convenient pouch format makes feeding easy at home, on outings or while travelling.'],
['No Preservatives', 'No artificial additives, just real fruit and vegetable blends.'],
['No Added Sugar', 'Naturally occurring sugars only, supporting healthy sugar tolerance.'],
['No Added Salt', 'Protects developing kidneys and builds healthy taste preferences.'],
]
: [
['Self-Feeding Skills', 'Easy-to-hold shape supports pincer grip development and independent eating.'],
['Super Grain Blend', 'Sprouted ragi, jowar, rice and corn provide carbohydrates and natural fibre.'],
['Pea Protein', 'Plant-based protein to support growing muscles.'],
['Real Veggie Powders', 'Carrot, sweet potato, spinach, pumpkin and more in every bite.'],
['No Preservatives', 'No artificial additives, no MSG, and no flavour enhancers.'],
['Balanced Macros', 'Designed with carbohydrates, proteins, fats, and vegetables for balanced snacking.'],
];

$storageItems = $productType === 'puree'
? [
['Unopened', 'Store in a cool, dry place away from direct sunlight. Do not refrigerate before opening.'],
['After Opening', 'Once opened, consume immediately or refrigerate and use within 24 hours.'],
['Temperature', 'Avoid storing above 30 C. Do not freeze.'],
['Shelf Life', 'Check the best-before date printed on the pouch.'],
]
: [
['Unopened', 'Store in a cool, dry place. Keep away from moisture and direct sunlight.'],
['After Opening', 'Reseal the bag tightly after each use. Best consumed within 2-3 days of opening.'],
['Temperature', 'Do not store in humid environments. Avoid temperatures above 30 C.'],
['Shelf Life', 'Check the best-before date printed on the pack.'],
];

$safetyItems = [
['Age Suitability', $product->age_group ? 'This product is suitable for ' . $product->age_group . '.' : 'Please refer to the packaging for age suitability information.'],
['Supervision Required', 'Always supervise your child while eating, especially for babies who are new to solids or self-feeding.'],
['Serve at Right Temperature', 'For purees, warm gently and always check temperature before serving. Never microwave in the pouch.'],
['Allergen Awareness', 'Please check the full ingredient list on the pack for allergen information before serving.'],
['Consult Your Paediatrician', 'If your child has any known allergies, medical conditions, or specific dietary needs, consult your doctor before introducing new foods.'],
['Hygiene', 'Always wash hands before preparing or serving food to your child.'],
];
@endphp

@section('title', 'NumNam - ' . $product->name)
@section('meta_description', Str::limit($product->short_description ?: $product->description, 160))
@section('og_image', $mainImage)

@section('structured_data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "description": "{{ Str::limit($product->description, 300) }}",
        "image": "{{ $mainImage }}",
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
        <div>
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm" id="mainImageWrap">
                @if($mainImage)
                <img
                    src="{{ $mainImage }}"
                    alt="{{ $product->name }}"
                    loading="eager"
                    id="mainProductImage"
                    style="width:100%;aspect-ratio:1/1;object-fit:contain;padding:1rem;transition:opacity 0.25s ease;">
                @else
                <div class="flex h-full w-full items-center justify-center" style="aspect-ratio:1/1;background:#f1f5f9;">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-slate-300">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                @endif
            </div>

            @if($gallery->count() > 1)
            <div class="mt-4 grid grid-cols-5 gap-2">
                @foreach($gallery as $i => $photo)
                <button
                    type="button"
                    class="product-thumb {{ $i === 0 ? 'active border-numnam-400' : 'border-slate-200' }} overflow-hidden rounded-xl border-2 bg-white shadow-sm transition-all duration-200 hover:border-numnam-400 focus:outline-none"
                    data-img="{{ $photo }}"
                    aria-label="{{ $product->name }} image {{ $i + 1 }}">
                    <img
                        src="{{ $photo }}"
                        alt="{{ $product->name }} view {{ $i + 1 }}"
                        loading="{{ $i < 3 ? 'eager' : 'lazy' }}"
                        style="width:100%;aspect-ratio:1/1;object-fit:contain;padding:4px;">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <div>
            <span class="inline-flex rounded-full border border-numnam-200 bg-numnam-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">
                {{ $product->age_group }} | {{ ucfirst($product->type) }}
            </span>

            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $product->name }}</h1>

            @if($product->badges && count($product->badges))
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($product->badges as $badge)
                <span class="inline-flex rounded-full border border-slate-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-slate-600">
                    {{ $badge }}
                </span>
                @endforeach
            </div>
            @endif

            <p class="mt-4 text-base leading-relaxed text-slate-600">{{ $product->short_description ?: $product->description }}</p>

            <div class="mt-5 flex flex-wrap items-center gap-3">
                <strong class="text-3xl font-bold text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                @if($product->sale_price)
                <del class="text-lg text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                <span class="inline-flex rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold text-white">
                    {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                </span>
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
            <p class="mt-3 inline-flex items-center rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-sm font-semibold text-rose-700">
                Out of Stock
            </p>
            @endif

            <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center" id="add-to-cart-form">
                @csrf
                <input class="h-11 w-24 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" type="number" min="1" name="qty" value="1" aria-label="Quantity">
                <button class="inline-flex h-11 items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit" name="action" value="add-to-cart" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    Add to Cart
                </button>
            </form>

            <button type="button" id="buy-now-btn" class="mt-3 inline-flex h-11 items-center justify-center rounded-full border border-slate-200 bg-white px-5 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-50" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                Buy Now
            </button>



            @auth
            @php
            $isWishlisted = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
            @endphp
            <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}" class="mt-4">
                @csrf
                <button
                    type="submit"
                    class="inline-flex h-11 items-center gap-2 rounded-full border px-5 text-sm font-semibold transition-colors duration-200 {{ $isWishlisted ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}"
                    aria-label="{{ $isWishlisted ? 'Remove from wishlist' : 'Add to wishlist' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    {{ $isWishlisted ? 'In Wishlist' : 'Add to Wishlist' }}
                </button>
            </form>
            @endauth

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

<div class="lightbox" id="productLightbox" hidden>
    <button type="button" class="lightbox-close" aria-label="Close">&times;</button>
    <img src="" alt="Product zoom">
</div>

<section class="section fade-in-up">
    @php
    $tabs = [
    ['id' => 'tab-ingredients', 'label' => 'Ingredients'],
    ['id' => 'tab-nutrition', 'label' => 'Nutrition Facts'],
    ['id' => 'tab-benefits', 'label' => 'Benefits'],
    ['id' => 'tab-storage', 'label' => 'Storage & Shelf Life'],
    ['id' => 'tab-safety', 'label' => 'Safety Notes'],
    ];
    @endphp

    <nav class="product-tab-nav nn-tab-nav flex flex-wrap gap-2" role="tablist">
        @foreach($tabs as $tab)
        <button
            class="product-tab nn-tab {{ $loop->first ? 'active' : '' }} inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-semibold transition-all duration-200"
            role="tab"
            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
            aria-controls="{{ $tab['id'] }}"
            data-tab="{{ $tab['id'] }}"
            type="button">
            {{ $tab['label'] }}
        </button>
        @endforeach
    </nav>

    <div class="nn-tab-panels mt-5 rounded-2xl border bg-white p-6 sm:p-8" style="border-color:#e8e9f0; box-shadow:0 2px 16px rgba(0,0,0,0.04);">
        <div class="product-tab-panel active" id="tab-ingredients" role="tabpanel">
            <h3 class="font-heading font-extrabold" style="font-size:1rem; color:#2D2D3F;">Ingredients</h3>
            <p class="mt-3 text-sm leading-relaxed" style="color:#5e6478;">{{ $product->ingredients ?: 'Ingredient details will be updated soon.' }}</p>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach($ingredientBadges as $badge)
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold" style="border-color:#4ECDC430; background:#F0FFF9; color:#2D2D3F;">
                    {{ $badge }}
                </span>
                @endforeach
            </div>
        </div>

        <div class="product-tab-panel" id="tab-nutrition" role="tabpanel">
            <h3 class="font-heading font-extrabold" style="font-size:1rem; color:#2D2D3F;">Nutrition Facts</h3>

            @if(is_array($nutrition) && !empty($nutrition))
            <div class="mt-4 overflow-hidden rounded-xl border" style="border-color:#e8e9f0;">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#F8F8FB;">
                            <th class="px-4 py-3 text-left font-heading font-bold" style="color:#2D2D3F; width:55%;">Nutrient</th>
                            <th class="px-4 py-3 text-right font-heading font-bold" style="color:#2D2D3F;">Per 100g</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nutrition as $key => $value)
                        <tr class="border-t" style="border-color:#f0f0f4;">
                            <td class="px-4 py-2.5 text-sm font-semibold" style="color:#2D2D3F;">{{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Nutrient' }}</td>
                            <td class="px-4 py-2.5 text-right text-sm" style="color:#5e6478;">{{ is_scalar($value) ? $value : json_encode($value) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="mt-3 text-sm" style="color:#5e6478;">Detailed nutrition values are coming soon.</p>
            @endif
        </div>

        <div class="product-tab-panel" id="tab-benefits" role="tabpanel">
            <h3 class="font-heading font-extrabold" style="font-size:1rem; color:#2D2D3F;">Why it's good for your child</h3>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach($benefits as [$title, $desc])
                <div class="rounded-xl border p-4" style="border-color:#4ECDC420; background:#F8FAFC;">
                    <p class="font-heading font-bold text-sm" style="color:#2D2D3F;">{{ $title }}</p>
                    <p class="mt-0.5 text-xs leading-relaxed" style="color:#5e6478;">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="product-tab-panel" id="tab-storage" role="tabpanel">
            <h3 class="font-heading font-extrabold" style="font-size:1rem; color:#2D2D3F;">Storage &amp; Shelf Life</h3>

            <div class="mt-4 space-y-3">
                @foreach($storageItems as [$label, $info])
                <div class="rounded-xl border bg-white p-4" style="border-color:#e8e9f0;">
                    <p class="font-heading font-bold text-sm" style="color:#2D2D3F;">{{ $label }}</p>
                    <p class="mt-0.5 text-xs leading-relaxed" style="color:#5e6478;">{{ $info }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="product-tab-panel" id="tab-safety" role="tabpanel">
            <h3 class="font-heading font-extrabold" style="font-size:1rem; color:#2D2D3F;">Safety Notes for Parents</h3>

            <div class="mt-4 space-y-3">
                @foreach($safetyItems as [$label, $info])
                <div class="rounded-xl border bg-white p-4" style="border-color:#e8e9f0;">
                    <p class="font-heading font-bold text-sm" style="color:#2D2D3F;">{{ $label }}</p>
                    <p class="mt-0.5 text-xs leading-relaxed" style="color:#5e6478;">{{ $info }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="section fade-in-up">
    @if($reviewCount > 0)
    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Customer Reviews</h2>
    <p class="mt-2 text-sm text-slate-600">
        {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
        @if($avgRating > 0)
        &middot; {{ $avgRating }} average
        @endif
    </p>

    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($reviews as $review)
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="rating" aria-label="{{ $review->rating }} out of 5 stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $review->rating ? 'filled' : 'empty' }}">&#9733;</span>
                    @endfor
            </div>

            @if($review->title)
            <h4 class="review-title">{{ $review->title }}</h4>
            @endif

            <p>{{ $review->body }}</p>
            <p class="mt-3 text-sm text-slate-600">
                <strong>{{ $review->user->name }}</strong> &middot; {{ $review->created_at->diffForHumans() }}
            </p>
        </article>
        @endforeach
    </div>
    @else
    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">What Parents Love About NumNam</h2>
    <p class="mt-2 text-sm text-slate-600">See why thousands of families trust us</p>

    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="rating">
                @for($i=0;$i<5;$i++)<span class="star filled">&#9733;</span>@endfor
            </div>
            <p>"My baby loves this! The texture is perfect for her age and I trust the clean ingredients."</p>
            <p class="mt-3 text-sm text-slate-600">
                <strong>Priya M.</strong> &middot; Verified Buyer
            </p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="rating">
                @for($i=0;$i<5;$i++)<span class="star filled">&#9733;</span>@endfor
            </div>
            <p>"Finally a baby food brand that's transparent about what goes in. Highly recommend!"</p>
            <p class="mt-3 text-sm text-slate-600">
                <strong>Rahul K.</strong> &middot; Verified Buyer
            </p>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="rating">
                @for($i=0;$i<4;$i++)<span class="star filled">&#9733;</span>@endfor
                    <span class="star empty">&#9733;</span>
            </div>
            <p>"Great quality and fast delivery. My baby finished the whole pack in a week!"</p>
            <p class="mt-3 text-sm text-slate-600">
                <strong>Sneha R.</strong> &middot; Verified Buyer
            </p>
        </article>
    </div>
    @endif

    @if(auth()->check())
    <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
        <h4 class="text-lg font-semibold text-slate-900">{{ $reviewCount > 0 ? 'Write a Review' : 'Be the First to Review' }}</h4>
        <p class="mt-2 text-sm text-slate-600">{{ $reviewCount > 0 ? 'Share your experience with NumNam' : 'Help other parents discover great products' }}</p>
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
                @error('rating')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="review-title">Title (optional)</label>
                <input type="text" id="review-title" name="title" class="input" maxlength="150" value="{{ old('title') }}" placeholder="Sum it up in a few words">
            </div>

            <div class="form-group">
                <label for="review-body">Your Review</label>
                <textarea id="review-body" name="body" class="input" rows="4" required minlength="10" maxlength="2000" placeholder="Share your experience...">{{ old('body') }}</textarea>
                @error('body')
                <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700">
                Submit Review
            </button>
        </form>
    </div>
    @else
    @if($reviewCount === 0)
    <div class="mt-8 rounded-3xl border border-numnam-100 bg-numnam-50 p-6 sm:p-7">
        <p class="text-sm text-numnam-800">
            <a href="{{ route('store.login') }}" class="font-semibold text-numnam-700 hover:text-numnam-600">Log in</a> to be the first to share your experience with NumNam
        </p>
    </div>
    @endif
    @endif
</section>

@if($related->isNotEmpty())
<section class="section fade-in-up">
    <h2 class="text-2xl font-semibold tracking-tight text-slate-900">You May Also Like</h2>
    <div class="mt-5 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($related as $item)
        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            @if($item->image_url)
            <div class="aspect-[4/3] bg-slate-100" style="background-image:url('{{ $item->image_url }}'); background-size:cover; background-position:center;"></div>
            @else
            <div class="flex aspect-[4/3] items-center justify-center bg-slate-100">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-slate-300">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
            </div>
            @endif
            <div class="p-5 sm:p-6">
                <h4 class="text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700">
                    <a href="{{ route('store.product.show', $item) }}">{{ $item->name }}</a>
                </h4>
                <div class="mt-3 flex items-center gap-2">
                    <strong class="text-lg text-slate-900">Rs {{ number_format($item->sale_price ?: $item->price, 0) }}</strong>
                    @if($item->sale_price)
                    <del class="text-sm text-slate-400">Rs {{ number_format($item->price, 0) }}</del>
                    @endif
                </div>
                <form method="POST" action="{{ route('store.cart.add', $item) }}" class="mt-4">
                    @csrf
                    <button class="inline-flex h-10 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">
                        Add to Cart
                    </button>
                </form>

            </div>
        </article>
        @endforeach
    </div>
</section>
@endif

@if($recentlyViewed->isNotEmpty())
<x-store.product-showcase
    title="Recently Viewed"
    subtitle="A quick way to revisit products you looked at recently."
    :products="$recentlyViewed"
    empty-text="Your recently viewed products will appear here." />
@endif

<!-- Guest Checkout Modal for Buy Now -->
<div id="guest-checkout-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto;">
    <div style="background: white; margin: 2rem auto; border-radius: 1.5rem; max-width: 500px; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #1e293b;">Quick Checkout</h2>
            <button type="button" id="close-modal" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">×</button>
        </div>

        <form id="guest-buy-now-form" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="ship_name" required placeholder="Your full name" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Email</label>
                <input type="email" name="email" required placeholder="your@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Phone</label>
                <input type="tel" name="ship_phone" required placeholder="10-digit number" pattern="[0-9]{10}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Address</label>
                <input type="text" name="ship_address" required placeholder="Street address" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">City</label>
                    <input type="text" name="ship_city" required placeholder="City" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">State</label>
                    <input type="text" name="ship_state" required placeholder="State" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Pincode</label>
                <input type="text" name="ship_pincode" required placeholder="6-digit pincode" pattern="[0-9]{6}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <p id="modal-message" style="font-size: 0.875rem; color: #64748b; display: none;"></p>

            <button type="submit" style="width: 100%; padding: 0.75rem; background: #16a34a; color: white; border: none; border-radius: 2rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">
                Continue to Payment
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Guest Buy Now Modal
    (function() {
        const buyNowBtn = document.getElementById('buy-now-btn');
        const modal = document.getElementById('guest-checkout-modal');
        const closeBtn = document.getElementById('close-modal');
        const form = document.getElementById('guest-buy-now-form');
        const message = document.getElementById('modal-message');

        if (!buyNowBtn || !modal) return;

        buyNowBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            form.reset();
            message.style.display = 'none';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                form.reset();
                message.style.display = 'none';
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            message.style.display = 'none';

            try {
                const response = await fetch('{{ route("store.checkout.guest-payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ship_name: formData.get('ship_name'),
                        ship_phone: formData.get('ship_phone'),
                        ship_address: formData.get('ship_address'),
                        ship_city: formData.get('ship_city'),
                        ship_state: formData.get('ship_state'),
                        ship_pincode: formData.get('ship_pincode'),
                        email: formData.get('email'),
                    }),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    message.textContent = result.message || 'Failed to create checkout';
                    message.style.color = '#dc2626';
                    message.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    return;
                }

                // Show Razorpay checkout
                if (typeof window.Razorpay === 'undefined') {
                    message.textContent = 'Unable to load Razorpay. Please refresh and try again.';
                    message.style.color = '#dc2626';
                    message.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    return;
                }

                const razorpayOptions = {
                    key: result.key_id,
                    amount: result.amount,
                    currency: result.currency,
                    order_id: result.razorpay_order_id,
                    customer_notification: 1,
                    handler: function(response) {
                        message.textContent = 'Payment successful! Order #' + result.order_number;
                        message.style.color = '#16a34a';
                        message.style.display = 'block';
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;

                        setTimeout(() => {
                            window.location.href = '/shop';
                        }, 2000);
                    },
                    prefill: {
                        name: result.customer_name,
                        email: result.customer_email,
                        contact: result.customer_phone,
                    },
                    theme: {
                        color: '#16a34a'
                    },
                };

                const razorpay = new window.Razorpay(razorpayOptions);
                razorpay.open();
            } catch (error) {
                message.textContent = error.message || 'Unable to process checkout';
                message.style.color = '#dc2626';
                message.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    })();
</script>

<script>
    (function() {
        var mainImg = document.getElementById('mainProductImage');
        if (!mainImg) {
            return;
        }

        var thumbs = document.querySelectorAll('.product-thumb[data-img]');
        thumbs.forEach(function(btn) {
            btn.addEventListener('click', function() {
                thumbs.forEach(function(b) {
                    b.style.borderColor = '#e2e8f0';
                    b.classList.remove('active');
                });

                btn.style.borderColor = '#fe7d94';
                btn.classList.add('active');
                mainImg.style.opacity = '0';

                setTimeout(function() {
                    mainImg.src = btn.dataset.img;
                    mainImg.style.opacity = '1';
                }, 120);
            });
        });

        var lightbox = document.getElementById('productLightbox');
        var lightboxImg = lightbox ? lightbox.querySelector('img') : null;

        if (lightbox && lightboxImg) {
            mainImg.style.cursor = 'zoom-in';

            mainImg.addEventListener('click', function() {
                lightboxImg.src = mainImg.src;
                lightbox.hidden = false;
            });

            var closeBtn = lightbox.querySelector('.lightbox-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    lightbox.hidden = true;
                });
            }

            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox) {
                    lightbox.hidden = true;
                }
            });
        }
    }());
</script>
@endsection