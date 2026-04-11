@extends('store.layouts.app')

@php
$productPlaceholders = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Purees/appi%20pooch%203.png'),
asset('assets/images/Purees/berry%20swush%203.png'),
];

$categoryImage = $category->image ?: $productPlaceholders[$category->id % count($productPlaceholders)];
@endphp

@section('title', 'NumNam - ' . $category->name)
@section('meta_description', 'Explore ' . $category->name . ' from NumNam. Shop clean-label, stage-wise products with transparent ingredients and parent-friendly convenience.')
@section('og_image', $categoryImage)

@section('structured_data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $category->name }}",
        "description": "Shop {{ $category->name }} from NumNam.",
        "url": "{{ route('store.category.show', $category) }}"
    }
</script>
@endsection

@section('content')
<section class="px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="mx-auto grid max-w-7xl gap-6 overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-[#fff9f2] via-white to-[#fff4e8] p-6 shadow-soft sm:p-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:p-12">
        <div>
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-numnam-700">Top Category</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $category->name }}</h1>
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">Discover our curated range of {{ strtolower($category->name) }} designed for growing families, with clean-label ingredients, reliable quality, and stage-aware nutrition.</p>
            <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5">
                    <svg class="h-4 w-4 text-numnam-600" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a3 3 0 110 6 3 3 0 010-6zM4.5 15.5A4.5 4.5 0 019 11h2a4.5 4.5 0 014.5 4.5V17h-11v-1.5z" />
                    </svg>
                    {{ $products->total() }} products
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5">
                    <span class="text-amber-400">★★★★★</span>
                    Rated 4.8 stars
                </span>
            </div>
        </div>
        <div class="aspect-[4/3] overflow-hidden rounded-3xl bg-slate-100" style="background-image:url('{{ $categoryImage }}'); background-size:cover; background-position:center;"></div>
    </div>
</section>

<section class="px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Browse {{ $category->name }}</h2>
                <p class="mt-1 text-sm text-slate-600">Handpicked products in a dedicated category page with clean, SEO-friendly URLs.</p>
            </div>
            <a href="{{ route('store.products', ['category' => $category->slug]) }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900">View all with filters</a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($products as $product)
            @php($placeholderImage = $product->image_url ?: $productPlaceholders[$loop->index % count($productPlaceholders)])
            <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <div class="aspect-[4/3] bg-slate-100" style="background-image:url('{{ $placeholderImage }}'); background-size:cover; background-position:center;"></div>
                <div class="p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $product->age_group }}</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900"><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h3>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 100) }}</p>
                    <div class="mt-4 flex items-center gap-2">
                        <strong class="text-lg text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                        @if($product->sale_price)
                        <del class="text-sm text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-4">
                        @csrf
                        <button class="btn btn-primary" type="submit">Add to Cart</button>
                    </form>
                    <x-store.social-proof customers="10,000+" rating="4.8" compact="true" />
                </div>
            </article>
            @empty
            <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600 md:col-span-2 xl:col-span-3">
                No active products found in this category yet.
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</section>

@if($relatedCategories->isNotEmpty())
<section class="px-4 pb-14 pt-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Explore More Categories</h2>
            <p class="mt-1 text-sm text-slate-600">Discover other popular categories parents shop most often.</p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($relatedCategories as $relatedCategory)
            <x-store.category-card :category="$relatedCategory" />
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection