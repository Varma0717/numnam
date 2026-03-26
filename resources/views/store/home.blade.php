@extends('store.layouts.app')

@section('title', 'NumNam - Home')

@section('content')
@php
$trustCards = [
[
'icon' => 'shield',
'title' => 'Doctor-Founded',
'subtitle' => 'Backed by European Nutrition',
],
[
'icon' => 'leaf',
'title' => 'Vegetable Forward',
'subtitle' => 'Rich in Veggies',
],
[
'icon' => 'drop',
'title' => 'No added sugar',
'subtitle' => 'Naturally sweet',
],
[
'icon' => 'spark',
'title' => 'No preservatives',
'subtitle' => 'Totally clean',
],
];

$productPlaceholders = [
asset('assets/images/product_1.png'),
asset('assets/images/product_2.png'),
asset('assets/images/product_3.png'),
asset('assets/images/product_4.png'),
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
    <div class="ticker-track">
        @php $tickerText = 'Fresh ingredients ★ Clean whole food ★ No preservatives ★ Stage-wise textures ★ Doctor founded ★ Subscription friendly ★ '; @endphp
        <p>{{ $tickerText }}</p>
        <p aria-hidden="true">{{ $tickerText }}</p>
    </div>
</section>

<section class="section trust-strip animate-fade-up">
    <div class="section-head">
        <div>
            <h3>Why Parents Trust Num Nam</h3>
        </div>
    </div>
    <div class="trust-grid stagger-children">
        @foreach($trustCards as $item)
        <article class="trust-card">
            <span class="trust-icon" aria-hidden="true">
                @switch($item['icon'])
                @case('shield')
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                    <path d="M9 12l2 2 4-4" />
                </svg>
                @break
                @case('leaf')
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 21c6 0 12-6 12-12V3h-6C6 3 3 6 3 12s3 9 3 9z" />
                    <path d="M7 17c3-3 6-6 11-8" />
                </svg>
                @break
                @case('drop')
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2s6 7 6 11a6 6 0 11-12 0c0-4 6-11 6-11z" />
                </svg>
                @break
                @default
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l1.9 4.9L19 9l-5.1 2.1L12 16l-1.9-4.9L5 9l5.1-2.1L12 2z" />
                    <path d="M5 19l1-2 2-1-2-1-1-2-1 2-2 1 2 1 1 2z" />
                    <path d="M19 19l.7-1.3L21 17l-1.3-.7L19 15l-.7 1.3L17 17l1.3.7L19 19z" />
                </svg>
                @endswitch
            </span>
            <div>
                <h4>{{ $item['title'] }}</h4>
                <p class="meta">{{ $item['subtitle'] }}</p>
            </div>
        </article>
        @endforeach
    </div>
</section>

{{-- Stats Counter --}}
<section class="section animate-fade-up">
    <div class="stats-row">
        <div class="stat-block">
            <div class="stat-number" data-count-to="10000">0<span class="suffix">+</span></div>
            <div class="stat-desc">Happy Families</div>
        </div>
        <div class="stat-block">
            <div class="stat-number" data-count-to="15">0<span class="suffix">+</span></div>
            <div class="stat-desc">Unique Products</div>
        </div>
        <div class="stat-block">
            <div class="stat-number" data-count-to="100">0<span class="suffix">%</span></div>
            <div class="stat-desc">Natural Ingredients</div>
        </div>
        <div class="stat-block">
            <div class="stat-number" data-count-to="4">0<span class="suffix">.9★</span></div>
            <div class="stat-desc">Average Rating</div>
        </div>
    </div>
</section>

<section class="section animate-fade-up">
    <div class="section-head">
        <div>
            <h3>Our Best Sellers</h3>
        </div>
        @if($featuredProducts->count() > 3)
        <div class="product-carousel-controls" aria-label="Best sellers controls">
            <button type="button" class="product-carousel-btn" data-carousel-prev aria-label="Previous products">&#10094;</button>
            <button type="button" class="product-carousel-btn" data-carousel-next aria-label="Next products">&#10095;</button>
        </div>
        @endif
    </div>
    @if($featuredProducts->isEmpty())
    <p class="meta">No featured products yet.</p>
    @else
    <div class="product-carousel" data-product-carousel>
        <div class="product-carousel-viewport">
            <div class="product-carousel-track" data-carousel-track>
                @foreach($featuredProducts as $product)
                @php($placeholderImage = $productPlaceholders[$loop->index % count($productPlaceholders)])
                <div class="product-carousel-item">
                    <article class="card hover-up">
                        <div class="media" style="background-image:url('{{ $placeholderImage }}'); background-size:cover;">
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
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</section>

{{-- How It Works --}}
<section class="section animate-fade-up">
    <div class="section-head">
        <div>
            <h3>How It Works</h3>
            <p class="section-sub">Getting started is simple</p>
        </div>
    </div>
    <div class="store-grid three how-it-works stagger-children">
        <div class="step-card">
            <h4>Choose Your Stage</h4>
            <p>Select products matched to your baby's age and developmental stage.</p>
        </div>
        <div class="step-card">
            <h4>We Prepare Fresh</h4>
            <p>Every batch is made with clean, whole ingredients — no preservatives ever.</p>
        </div>
        <div class="step-card">
            <h4>Delivered to You</h4>
            <p>Fast, reliable delivery straight to your door. Subscribe for extra savings.</p>
        </div>
    </div>
</section>

<section class="section animate-fade-up">
    <div class="section-head">
        <div>
            <h3>Subscription Plans</h3>
        </div>
    </div>
    <div class="store-grid three stagger-children">
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

<section class="section animate-fade-up">
    <div class="section-head">
        <div>
            <h3>Testimonials</h3>
        </div>
    </div>
    <div class="store-grid three stagger-children">
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