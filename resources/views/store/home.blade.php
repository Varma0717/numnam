@extends('store.layouts.app')

@section('title', 'NumNam - Home')

@section('content')
@php
    $resolveTrustImage = function (string $name, string $fallback): string {
        $candidates = [$name, $name . '.png', $name . '.jpg', $name . '.jpeg', $name . '.webp'];
        foreach ($candidates as $candidate) {
            if (file_exists(public_path('images/' . $candidate))) {
                return asset('images/' . $candidate);
            }
        }

        return asset($fallback);
    };

    $trustCards = [
        [
            'file' => 'weavy-Gemini-3-(Nano-Banana-Pro)-2026-02',
            'fallback' => 'assets/images/product_1.png',
            'title' => 'Doctor-Founded',
            'subtitle' => 'Backed by European Nutrition',
        ],
        [
            'file' => '13_edited.png',
            'fallback' => 'assets/images/product_2.png',
            'title' => 'Vegetable Forward',
            'subtitle' => 'Rich in Veggies',
        ],
        [
            'file' => 'no-sugar-free-vector-icon-cubes-circle-added-product-package-design-217234312_edited.png',
            'fallback' => 'assets/images/product_3.png',
            'title' => 'No added sugar',
            'subtitle' => 'Naturally sweet',
        ],
        [
            'file' => 'no-added-preservatives-icon-chemical-art',
            'fallback' => 'assets/images/product_4.png',
            'title' => 'No preservatives',
            'subtitle' => 'Totally clean',
        ],
    ];
@endphp

<section class="hero-slider in-view">
    <div class="hero-slide-track" data-hero-track>
        <article class="hero-slide" style="background-image:url('{{ asset('assets/images/hero.jpg') }}');">
            <div class="hero-slide-overlay">
                <div class="hero-slide-copy">
                    <span class="kicker">NumNam Nutrition</span>
                    <h1>{{ $homepageSections['hero_title'] ?? 'Smart baby nutrition, delivered with parent-friendly convenience.' }}</h1>
                    <p>{{ $homepageSections['hero_subtitle'] ?? 'Discover stage-wise foods, subscriptions, and transparent ingredients built for modern families.' }}</p>
                    <div class="hero-actions">
                        <a class="cta-btn" href="{{ route('store.products') }}">Shop Products</a>
                        <a class="btn-soft" href="{{ route('store.pricing') }}">Build Subscription</a>
                    </div>
                </div>
            </div>
        </article>
        <article class="hero-slide" style="background-image:url('{{ asset('assets/images/hero_1.jpg') }}');">
            <div class="hero-slide-overlay">
                <div class="hero-slide-copy">
                    <span class="kicker">Stage-Wise Growth</span>
                    <h2>Nutrition crafted for each milestone.</h2>
                    <p>From first tastes to self-feeding confidence, every bite is made to support healthy development.</p>
                </div>
            </div>
        </article>
        <article class="hero-slide" style="background-image:url('{{ asset('assets/images/background_img.jpg') }}');">
            <div class="hero-slide-overlay">
                <div class="hero-slide-copy">
                    <span class="kicker">Parent Friendly</span>
                    <h2>Fast delivery, clean labels, no mealtime stress.</h2>
                    <p>Enjoy predictable subscriptions, transparent ingredients, and support built for modern families.</p>
                </div>
            </div>
        </article>
    </div>

    <div class="hero-slider-controls" aria-label="Hero slider controls">
        <button type="button" class="hero-nav prev" data-hero-prev aria-label="Previous slide">&#10094;</button>
        <button type="button" class="hero-nav next" data-hero-next aria-label="Next slide">&#10095;</button>
    </div>

    <div class="hero-slider-pagination" data-hero-pagination>
        <button type="button" class="hero-dot is-active" data-hero-dot="0" aria-label="Go to slide 1"></button>
        <button type="button" class="hero-dot" data-hero-dot="1" aria-label="Go to slide 2"></button>
        <button type="button" class="hero-dot" data-hero-dot="2" aria-label="Go to slide 3"></button>
    </div>
</section>

<section class="section ticker-strip">
    <p>Fresh ingredients | Clean whole food | No preservatives | Stage-wise textures | Doctor founded | Subscription friendly</p>
</section>

<section class="section trust-strip fade-in-up">
    <div class="section-head"><div><h3>Why Parents Trust Num Nam</h3></div></div>
    <div class="trust-grid">
        @foreach($trustCards as $item)
            <article class="trust-card">
                <img src="{{ $resolveTrustImage($item['file'], $item['fallback']) }}" alt="{{ $item['title'] }} icon">
                <div>
                    <h4>{{ $item['title'] }}</h4>
                    <p class="meta">{{ $item['subtitle'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head"><div><h3>Our Best Sellers</h3></div></div>
    <div class="store-grid three">
        @forelse($featuredProducts as $product)
            <article class="card hover-up">
                <div class="media" style="background-image:url('{{ $product->image ?: '' }}'); background-size:cover;">
                    @if($product->sale_price)
                        <span class="badge-sale">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                    @endif
                </div>
                <div class="card-body">
                    <span class="kicker">{{ $product->age_group }}</span>
                    <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 90) }}</p>
                    <div class="price">
                        <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                        @if($product->sale_price)
                            <del>Rs {{ number_format($product->price, 0) }}</del>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.cart.add', $product) }}" class="store-actions">
                        @csrf
                        <button class="btn-primary" type="submit">Add to Cart</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="meta">No featured products yet.</p>
        @endforelse
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head"><div><h3>Subscription Plans</h3></div></div>
    <div class="store-grid three">
        @foreach($plans as $plan)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $plan->name }}</h4>
                    <p class="meta">{{ $plan->description }}</p>
                    <div class="price"><strong>Rs {{ number_format($plan->price, 0) }}</strong></div>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head"><div><h3>Testimonials</h3></div></div>
    <div class="store-grid three">
        @foreach($testimonials as $testimonial)
            <article class="card testimonial-card">
                <div class="card-body">
                    <p>"{{ $testimonial['quote'] }}"</p>
                    <p class="meta"><strong>{{ $testimonial['name'] }}</strong></p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
