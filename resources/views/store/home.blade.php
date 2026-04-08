@extends('store.layouts.app')

@section('title', 'NumNam - Fueling Tiny Adventures')

@section('content')

{{-- ===== HERO — compact, flat candy style ===== --}}
<section class="hero-fullbleed in-view" style="background:#FFF0F5;">
    <div class="hero-kids-visual pointer-events-none hidden md:flex" aria-hidden="true">
        <div class="hero-kids-frame">
            <img src="{{ asset('assets/images/0-18months.webp') }}" alt="" loading="eager" class="hero-kids-image">
        </div>
    </div>

    <div class="hero-content">
        <div style="max-width:560px;">
            <p class="mb-3 inline-flex items-center gap-2 rounded-full border-2 border-[#FFD93D] bg-white px-4 py-1.5 font-heading text-xs font-bold uppercase tracking-widest" style="color:#FF6B8A;">
                Fueling Tiny Adventures
            </p>
            <h1 class="font-heading text-3xl font-bold leading-tight sm:text-4xl lg:text-5xl" style="color:#2D2D3F;">
                {{ $homepageSections['hero_title'] ?? 'Clean, yummy baby food made with love.' }}
            </h1>
            <p class="mt-4 max-w-lg text-base leading-relaxed sm:text-lg" style="color:#5e6478;">
                {{ $homepageSections['hero_subtitle'] ?? 'Stage-wise nutrition, transparent ingredients, and easy subscriptions for busy families.' }}
            </p>
            <div class="mt-7 flex flex-wrap items-center gap-3">
                <a class="cta-btn" href="{{ route('store.products') }}">Shop Now</a>
                <a class="btn-ghost" href="{{ route('store.pricing') }}">Subscriptions</a>
            </div>
        </div>
    </div>
</section>

{{-- ===== CLOUD DIVIDER ===== --}}
@include('store.partials.cloud-divider', ['color' => '#FFFDF8'])

{{-- ===== CATEGORIES — 2×2 mobile, 4-col desktop ===== --}}
@if($topCategories->isNotEmpty())
<section class="px-4 py-8 sm:px-6 lg:px-8" style="background:#FFFDF8;">
    <div class="mx-auto max-w-6xl text-center">
        <h2 class="font-heading text-2xl font-bold sm:text-3xl" style="color:#2D2D3F;">Shop by Category</h2>
        <p class="mx-auto mt-2 max-w-md text-sm" style="color:#5e6478;">Find the perfect products for every stage of your little one's journey.</p>

        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach($topCategories->take(4) as $category)
            <a href="{{ route('store.products', ['category' => $category->slug]) }}"
                class="group block rounded-[2rem] border-3 bg-white p-4 text-center transition-transform duration-200 hover:-translate-y-1"
                style="border-color:#FFD6E5;">
                @if($category->image)
                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" loading="lazy"
                    class="mx-auto mb-3 h-20 w-20 rounded-full object-cover sm:h-24 sm:w-24"
                    style="border:3px solid #FFD93D;">
                @endif
                <h3 class="font-heading text-sm font-bold sm:text-base" style="color:#2D2D3F;">{{ $category->name }}</h3>
                @if($category->products_count)
                <p class="mt-1 text-xs" style="color:#9B8EC4;">{{ $category->products_count }} products</p>
                @endif
            </a>
            @endforeach
        </div>

        <a href="{{ route('store.products') }}" class="mt-6 inline-flex items-center gap-1 font-heading text-sm font-bold" style="color:#FF6B8A;">
            View All Products &rarr;
        </a>
    </div>
</section>
@endif

{{-- ===== FEATURED PRODUCTS STRIP ===== --}}
<section class="px-4 py-8 sm:px-6 lg:px-8" style="background:#FFFDF8;">
    <div class="mx-auto max-w-6xl">
        <div class="mb-5 flex items-end justify-between">
            <div>
                <h2 class="font-heading text-2xl font-bold sm:text-3xl" style="color:#2D2D3F;">Best Sellers</h2>
                <p class="mt-1 text-sm" style="color:#5e6478;">The products families reorder most.</p>
            </div>
            <a href="{{ route('store.products') }}" class="hidden font-heading text-sm font-bold sm:inline-flex" style="color:#FF6B8A;">See all &rarr;</a>
        </div>

        @if($bestSellerProducts->isNotEmpty())
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach($bestSellerProducts->take(4) as $product)
            <a href="{{ route('store.product.show', $product->slug) }}"
                class="group block overflow-hidden rounded-[2rem] border-3 bg-white transition-transform duration-200 hover:-translate-y-1"
                style="border-color:#FFD6E5;">
                <div class="aspect-square overflow-hidden" style="background:#FFF0F5;">
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" loading="lazy"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                </div>
                <div class="p-3 sm:p-4">
                    <h3 class="font-heading text-sm font-bold leading-snug sm:text-base" style="color:#2D2D3F;">{{ $product->name }}</h3>
                    <p class="mt-1 font-heading text-sm font-bold" style="color:#FF6B8A;">Rs {{ number_format($product->price, 0) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-center text-sm" style="color:#5e6478;">Best sellers coming soon!</p>
        @endif

        <a href="{{ route('store.products') }}" class="mt-4 block text-center font-heading text-sm font-bold sm:hidden" style="color:#FF6B8A;">See all products &rarr;</a>
    </div>
</section>

@endsection