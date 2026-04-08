@extends('store.layouts.app')

@section('title', 'NumNam - Fueling Tiny Adventures')

@section('content')
@php
$heroSlides = [
['image' => asset('assets/images/hero.png'), 'title' => $homepageSections['hero_title'] ?? 'Clean, yummy baby food made with love.', 'subtitle' => $homepageSections['hero_subtitle'] ?? 'Stage-wise nutrition, transparent ingredients, and easy subscriptions for busy families.'],
['image' => asset('assets/images/hero_1.png'), 'title' => 'Inspired by European Nutrition Standards', 'subtitle' => 'Doctor-founded recipes crafted with real fruits and vegetables for your little one.'],
['image' => asset('assets/images/hero_2.png'), 'title' => 'Fueling Tiny Adventures', 'subtitle' => 'From smooth purees to crunchy puffs — feeding milestones made simple.'],
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
                <div class="hero-slide-inner">
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
<section class="px-4 py-12 sm:px-6 sm:py-16 lg:px-8" style="background:linear-gradient(180deg, #FFF0F5 0%, #FFFDF8 100%);">
    <div class="mx-auto max-w-6xl">
        <h2 class="text-center font-heading text-3xl font-bold sm:text-4xl" style="color:#FF6B8A;">Why Parents Trust NumNam</h2>
        <p class="mx-auto mt-3 max-w-lg text-center text-base" style="color:#5e6478;">Clean ingredients, doctor-approved recipes, and zero compromise.</p>
        <div class="mt-10 grid grid-cols-2 gap-5 lg:grid-cols-4">
            <div class="rounded-[2rem] border-3 p-6 text-center transition-transform duration-200 hover:-translate-y-1" style="border-color:#FF6B8A; background:#FFF0F5;">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full" style="background:#fff; box-shadow: 0 4px 0 rgba(255,107,138,0.15);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#FF6B8A" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <h3 class="font-heading text-base font-bold sm:text-lg" style="color:#FF6B8A;">Doctor-Founded</h3>
                <p class="mt-2 text-sm" style="color:#5e6478;">Backed by European Nutrition Standards</p>
            </div>
            <div class="rounded-[2rem] border-3 p-6 text-center transition-transform duration-200 hover:-translate-y-1" style="border-color:#4ECDC4; background:#ECFFF4;">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full" style="background:#fff; box-shadow: 0 4px 0 rgba(78,205,196,0.15);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#4ECDC4" stroke-width="2">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c1.85 0 3.58-.5 5.07-1.38" />
                        <path d="M17 8c-2 0-5 2.5-5 6" />
                        <path d="M15 2c2.5 4 3 8.5 1 13" />
                    </svg>
                </div>
                <h3 class="font-heading text-base font-bold sm:text-lg" style="color:#4ECDC4;">Vegetable Forward</h3>
                <p class="mt-2 text-sm" style="color:#5e6478;">Rich in Real Veggies & Fruits</p>
            </div>
            <div class="rounded-[2rem] border-3 p-6 text-center transition-transform duration-200 hover:-translate-y-1" style="border-color:#FFD93D; background:#FFFBF0;">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full" style="background:#fff; box-shadow: 0 4px 0 rgba(255,217,61,0.2);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#FFD93D" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07" />
                    </svg>
                </div>
                <h3 class="font-heading text-base font-bold sm:text-lg" style="color:#E5A800;">No Added Sugar</h3>
                <p class="mt-2 text-sm" style="color:#5e6478;">Naturally Sweet, Always Clean</p>
            </div>
            <div class="rounded-[2rem] border-3 p-6 text-center transition-transform duration-200 hover:-translate-y-1" style="border-color:#9B8EC4; background:#F5F0FF;">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full" style="background:#fff; box-shadow: 0 4px 0 rgba(155,142,196,0.15);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#9B8EC4" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                    </svg>
                </div>
                <h3 class="font-heading text-base font-bold sm:text-lg" style="color:#9B8EC4;">No Preservatives</h3>
                <p class="mt-2 text-sm" style="color:#5e6478;">Totally Clean, Totally Safe</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== CLOUD DIVIDER ===== --}}
@include('store.partials.cloud-divider', ['color' => '#FFCC25'])

{{-- ===== BEST SELLERS ===== --}}
<section class="px-4 py-12 sm:px-6 sm:py-16 lg:px-8" style="background:#FFCC25;">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <h2 class="font-heading text-3xl font-bold sm:text-4xl" style="color:#fff;">Best Sellers</h2>
                <p class="mt-2 text-base" style="color:#5e6478;">The products families reorder most.</p>
            </div>
            <a href="{{ route('store.products') }}" class="hidden rounded-full border-2 px-4 py-2 font-heading text-sm font-bold transition-colors duration-200 hover:bg-[#FFF0F5] sm:inline-flex" style="color:#fff; border-color:#fff;">See all &rarr;</a>
        </div>

        @if($bestSellerProducts->isNotEmpty())
        <div class="grid grid-cols-2 gap-4 sm:gap-5 lg:grid-cols-4">
            @foreach($bestSellerProducts->take(4) as $product)
            @php($prodImage = $product->image ? asset($product->image) : $productPlaceholders[$loop->index % count($productPlaceholders)])
            @php($prodColors = [['bg' => '#FFF0F5', 'border' => '#FF6B8A'], ['bg' => '#ECFFF4', 'border' => '#4ECDC4'], ['bg' => '#FFFBF0', 'border' => '#FFD93D'], ['bg' => '#F5F0FF', 'border' => '#9B8EC4']])
            @php($pc = $prodColors[$loop->index % 4])
            <a href="{{ route('store.product.show', $product->slug) }}"
                class="group block overflow-hidden rounded-[2rem] border-3 bg-white transition-transform duration-200 hover:-translate-y-1"
                style="border-color:{{ $pc['border'] }};">
                <div class="aspect-square overflow-hidden" style="background:{{ $pc['bg'] }};">
                    <img src="{{ $prodImage }}" alt="{{ $product->name }}" loading="lazy"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                </div>
                <div class="p-4 sm:p-5">
                    <h3 class="font-heading text-base font-bold leading-snug sm:text-lg" style="color:#2D2D3F;">{{ $product->name }}</h3>
                    <p class="mt-2 font-heading text-base font-bold sm:text-lg" style="color:#FF6B8A;">Rs {{ number_format($product->price, 0) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-center text-base" style="color:#5e6478;">Best sellers coming soon!</p>
        @endif

        <a href="{{ route('store.products') }}" class="mt-6 block text-center font-heading text-base font-bold sm:hidden" style="color:#fff;">See all products &rarr;</a>
    </div>
</section>

{{-- ===== SUBSCRIPTION PLANS ===== --}}
<section class="px-4 py-12 sm:px-6 sm:py-16 lg:px-8" style="background:linear-gradient(180deg, #F5F0FF 0%, #ECFFF4 100%);">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <h2 class="font-heading text-3xl font-bold sm:text-4xl" style="color:#4ECDC4;">Subscription Plans</h2>
                <p class="mt-2 text-base" style="color:#5e6478;">Convenient recurring deliveries — never run out of your baby's favourites.</p>
            </div>
            <a href="{{ route('store.pricing') }}" class="hidden rounded-full border-2 px-4 py-2 font-heading text-sm font-bold transition-colors duration-200 hover:bg-[#ECFFF4] sm:inline-flex" style="color:#4ECDC4; border-color:#4ECDC4;">View all plans &rarr;</a>
        </div>

        @if($plans->isNotEmpty())
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-{{ min($plans->count(), 3) }}">
            @foreach($plans->take(3) as $plan)
            @php($planColors = [['bg' => '#FFF0F5', 'border' => '#FF6B8A', 'accent' => '#FF6B8A'], ['bg' => '#ECFFF4', 'border' => '#4ECDC4', 'accent' => '#4ECDC4'], ['bg' => '#F5F0FF', 'border' => '#9B8EC4', 'accent' => '#9B8EC4']])
            @php($plc = $planColors[$loop->index % 3])
            @php($cycleLabel = match($plan->billing_cycle) { 'one_time' => 'One-time', 'weekly' => '/ week', 'monthly' => '/ month', 'quarterly' => '/ quarter', 'yearly' => '/ year', default => '/ ' . str_replace('_', ' ', $plan->billing_cycle) })
            <div class="rounded-[2rem] border-3 bg-white p-6 text-center transition-transform duration-200 hover:-translate-y-1 sm:p-8"
                style="border-color:{{ $plc['border'] }};">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full" style="background:{{ $plc['bg'] }};">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="{{ $plc['accent'] }}" stroke-width="2">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                        <line x1="12" y1="22.08" x2="12" y2="12" />
                    </svg>
                </div>
                <h3 class="font-heading text-xl font-bold" style="color:#2D2D3F;">{{ $plan->name }}</h3>
                @if($plan->description)
                <p class="mx-auto mt-2 max-w-xs text-sm" style="color:#5e6478;">{{ $plan->description }}</p>
                @endif
                <p class="mt-4 font-heading text-3xl font-bold" style="color:{{ $plc['accent'] }};">
                    Rs {{ number_format($plan->price, 0) }}
                    <span class="text-base font-normal" style="color:#5e6478;">{{ $cycleLabel }}</span>
                </p>
                @if($plan->features && count($plan->features))
                <ul class="mx-auto mt-4 max-w-xs space-y-2 text-left text-sm" style="color:#5e6478;">
                    @foreach($plan->features as $feature)
                    <li class="flex items-start gap-2">
                        <svg class="mt-0.5 shrink-0" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $plc['accent'] }}" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif
                <a href="{{ route('store.pricing') }}" class="mt-6 inline-flex items-center gap-1 rounded-full border-2 px-6 py-2.5 font-heading text-sm font-bold transition-colors duration-200 hover:text-white"
                    style="color:{{ $plc['accent'] }}; border-color:{{ $plc['accent'] }}; --hover-bg:{{ $plc['accent'] }};"
                    onmouseover="this.style.backgroundColor=this.style.getPropertyValue('--hover-bg')" onmouseout="this.style.backgroundColor='transparent'">
                    Subscribe Now
                </a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-base" style="color:#5e6478;">Subscription plans coming soon!</p>
        @endif

        <a href="{{ route('store.pricing') }}" class="mt-6 block text-center font-heading text-base font-bold sm:hidden" style="color:#4ECDC4;">View all plans &rarr;</a>
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