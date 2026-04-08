@extends('store.layouts.app')

@section('title', 'NumNam - Fueling Tiny Adventures')

@section('content')
@php
$heroSlides = [
['image' => asset('assets/images/hero.jpg'), 'title' => $homepageSections['hero_title'] ?? 'Clean, yummy baby food made with love.', 'subtitle' => $homepageSections['hero_subtitle'] ?? 'Stage-wise nutrition, transparent ingredients, and easy subscriptions for busy families.'],
['image' => asset('assets/images/hero_1.jpg'), 'title' => 'Inspired by European Nutrition Standards', 'subtitle' => 'Doctor-founded recipes crafted with real fruits and vegetables for your little one.'],
['image' => asset('assets/images/baby-choosing-what-eat-alone.jpg'), 'title' => 'Fueling Tiny Adventures', 'subtitle' => 'From smooth purees to crunchy puffs — feeding milestones made simple.'],
];
$productPlaceholders = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'),
];
$categoryPlaceholders = [
asset('assets/images/Purees/appi%20pooch%201.png'),
asset('assets/images/Purees/berry%20swush%201.png'),
asset('assets/images/Purees/brocco%20pop%201.png'),
asset('assets/images/Purees/mangy%20chewy%201.png'),
];
@endphp

{{-- ===== HERO SLIDER — full-width, 3 slides ===== --}}
<section class="hero-slider" id="heroSlider">
    <div class="hero-slide-track" id="heroTrack">
        @foreach($heroSlides as $i => $slide)
        <div class="hero-slide {{ $i === 0 ? 'is-active' : '' }}" style="background-image:url('{{ $slide['image'] }}');">
            <div class="hero-slide-overlay">
                <div class="hero-slide-copy">
                    <h1>{{ $slide['title'] }}</h1>
                    <p>{{ $slide['subtitle'] }}</p>
                    <div class="hero-slide-btns">
                        <a class="cta-btn" href="{{ route('store.products') }}">Shop Now</a>
                        <a class="hero-btn-outline" href="{{ route('store.pricing') }}">Subscriptions</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="hero-slider-controls">
        <button class="hero-nav" id="heroPrev" aria-label="Previous slide">&#8249;</button>
        <button class="hero-nav" id="heroNext" aria-label="Next slide">&#8250;</button>
    </div>
    <div class="hero-slider-pagination" id="heroDots">
        @foreach($heroSlides as $i => $slide)
        <button class="hero-dot {{ $i === 0 ? 'is-active' : '' }}" data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
</section>

{{-- ===== BLURBS — Why Parents Trust NumNam ===== --}}
<section class="px-4 py-10 sm:px-6 lg:px-8" style="background:#FFFDF8;">
    <div class="mx-auto max-w-6xl">
        <h2 class="text-center font-heading text-2xl font-bold sm:text-3xl" style="color:#2D2D3F;">Why Parents Trust NumNam</h2>
        <div class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="rounded-[2rem] border-3 bg-white p-5 text-center" style="border-color:#FFD6E5;">
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full" style="background:#FFF0F5;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FF6B8A" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <h3 class="font-heading text-sm font-bold sm:text-base" style="color:#2D2D3F;">Doctor-Founded</h3>
                <p class="mt-1 text-xs" style="color:#5e6478;">Backed by European Nutrition</p>
            </div>
            <div class="rounded-[2rem] border-3 bg-white p-5 text-center" style="border-color:#C8F7DC;">
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full" style="background:#ECFFF4;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#4ECDC4" stroke-width="2">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.85 0 3.58-.5 5.07-1.38" />
                        <path d="M17 8c-2 0-5 2.5-5 6" />
                        <path d="M15 2c2.5 4 3 8.5 1 13" />
                    </svg>
                </div>
                <h3 class="font-heading text-sm font-bold sm:text-base" style="color:#2D2D3F;">Vegetable Forward</h3>
                <p class="mt-1 text-xs" style="color:#5e6478;">Rich in Veggies</p>
            </div>
            <div class="rounded-[2rem] border-3 bg-white p-5 text-center" style="border-color:#FFE8B8;">
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full" style="background:#FFFBF0;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#FFD93D" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                    </svg>
                </div>
                <h3 class="font-heading text-sm font-bold sm:text-base" style="color:#2D2D3F;">No Added Sugar</h3>
                <p class="mt-1 text-xs" style="color:#5e6478;">Naturally sweet</p>
            </div>
            <div class="rounded-[2rem] border-3 bg-white p-5 text-center" style="border-color:#DDD6F3;">
                <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-full" style="background:#F5F0FF;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9B8EC4" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                    </svg>
                </div>
                <h3 class="font-heading text-sm font-bold sm:text-base" style="color:#2D2D3F;">No Preservatives</h3>
                <p class="mt-1 text-xs" style="color:#5e6478;">Totally clean</p>
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
            @php($catImage = $category->image ? asset($category->image) : $categoryPlaceholders[$loop->index % count($categoryPlaceholders)])
            <a href="{{ route('store.products', ['category' => $category->slug]) }}"
                class="group block rounded-[2rem] border-3 bg-white p-4 text-center transition-transform duration-200 hover:-translate-y-1"
                style="border-color:#FFD6E5;">
                <img src="{{ $catImage }}" alt="{{ $category->name }}" loading="lazy"
                    class="mx-auto mb-3 h-20 w-20 rounded-full object-cover sm:h-24 sm:w-24"
                    style="border:3px solid #FFD93D;">
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
            @php($prodImage = $product->image ? asset($product->image) : $productPlaceholders[$loop->index % count($productPlaceholders)])
            <a href="{{ route('store.product.show', $product->slug) }}"
                class="group block overflow-hidden rounded-[2rem] border-3 bg-white transition-transform duration-200 hover:-translate-y-1"
                style="border-color:#FFD6E5;">
                <div class="aspect-square overflow-hidden" style="background:#FFF0F5;">
                    <img src="{{ $prodImage }}" alt="{{ $product->name }}" loading="lazy"
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

@section('scripts')
<script>
    (function() {
        var track = document.getElementById('heroTrack');
        var slides = track ? track.querySelectorAll('.hero-slide') : [];
        var dots = document.querySelectorAll('.hero-dot');
        var prev = document.getElementById('heroPrev');
        var next = document.getElementById('heroNext');
        if (!track || slides.length < 2) return;
        var current = 0,
            total = slides.length,
            timer;

        function go(i) {
            current = ((i % total) + total) % total;
            track.style.transform = 'translateX(-' + (current * 100) + '%)';
            dots.forEach(function(d, idx) {
                d.classList.toggle('is-active', idx === current);
            });
        }

        function auto() {
            timer = setInterval(function() {
                go(current + 1);
            }, 5000);
        }

        function stop() {
            clearInterval(timer);
        }
        if (next) next.addEventListener('click', function() {
            stop();
            go(current + 1);
            auto();
        });
        if (prev) prev.addEventListener('click', function() {
            stop();
            go(current - 1);
            auto();
        });
        dots.forEach(function(d) {
            d.addEventListener('click', function() {
                stop();
                go(+d.dataset.slide);
                auto();
            });
        });
        auto();
    })();
</script>
@endsection