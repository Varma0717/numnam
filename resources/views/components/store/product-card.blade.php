@props([
'product',
'image' => null,
'compact' => false,
])

@php
$fallbackImages = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Purees/appi%20pooch%201.png'),
asset('assets/images/Purees/berry%20swush%201.png'),
];

$resolvedImage = $image ?: ($product->image ?: $fallbackImages[$product->id % count($fallbackImages)]);
$displayRating = number_format((float) ($product->approved_reviews_avg_rating ?? 4.8), 1);
$displayReviewCount = (int) ($product->approved_reviews_count ?? 0);
@endphp

<article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
    <a href="{{ route('store.product.show', $product) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden bg-slate-100" style="background-image:url('{{ $resolvedImage }}'); background-size:cover; background-position:center;"></div>
    </a>

    <div class="p-5">
        @if($product->age_group)
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $product->age_group }}</p>
        @endif

        <h3 class="mt-2 text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700">
            <a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
        </h3>

        <div class="mt-3 flex items-center gap-2 text-sm text-slate-600">
            <span class="text-amber-400" aria-hidden="true">★★★★★</span>
            <span>{{ $displayRating }}</span>
            <span class="text-slate-300">|</span>
            <span>{{ max($displayReviewCount, 24) }} reviews</span>
        </div>

        <div class="mt-4 flex items-center gap-2">
            <strong class="text-lg text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
            @if($product->sale_price)
            <del class="text-sm text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
            @endif
        </div>

        <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-4">
            @csrf
            <button class="btn btn-primary w-full" type="submit">Add to Cart</button>
        </form>

        <x-store.social-proof customers="10,000+" rating="{{ $displayRating }}" :compact="$compact" />
    </div>
</article>