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

$whyChooseUsBenefits = [
[
'icon' => 'truck',
'title' => 'Fast Delivery',
'description' => 'Quick dispatch and dependable delivery across major cities.',
],
[
'icon' => 'lock',
'title' => 'Secure Payment',
'description' => 'Protected checkout with encrypted transactions and trusted gateways.',
],
[
'icon' => 'badge',
'title' => 'Quality Products',
'description' => 'Carefully sourced products with strict quality checks at every step.',
],
[
'icon' => 'refresh',
'title' => 'Easy Returns',
'description' => 'Hassle-free returns with responsive support when plans change.',
],
];

$customerReviews = [
[
'name' => 'Ananya Reddy',
'rating' => 5,
'comment' => 'NumNam has made meal planning so much easier. My baby loves the textures and I love the clean ingredients.',
],
[
'name' => 'Rohit Sharma',
'rating' => 5,
'comment' => 'Fast delivery and consistent quality every time. The subscription option is super convenient for busy parents.',
],
[
'name' => 'Megha Patel',
'rating' => 4,
'comment' => 'Great variety and transparent labels. Support team is quick to help when we have questions.',
],
[
'name' => 'Karan & Nisha',
'rating' => 5,
'comment' => 'We switched to NumNam recently and saw a big improvement in our little one\'s mealtime routine.',
],
];

$averageCustomerRating = 4.8;

$userGeneratedContent = [
[
'image' => asset('assets/images/Purees/appi%20pooch%201.png'),
'handle' => '@ananya.parents',
'caption' => 'Our breakfast routine is finally stress-free. Baby actually finishes the bowl.',
],
[
'image' => asset('assets/images/Purees/berry%20swush%201.png'),
'handle' => '@littlebiteswithriya',
'caption' => 'A clean-label option we feel good about keeping stocked every week.',
],
[
'image' => asset('assets/images/Purees/brocco%20pop%201.png'),
'handle' => '@mealtimesbymegha',
'caption' => 'Texture progression feels so much easier when the products are built by stage.',
],
[
'image' => asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
'handle' => '@raisingwithrohit',
'caption' => 'Fast delivery and zero guesswork. Exactly what busy parents need.',
],
[
'image' => asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
'handle' => '@numnam.family',
'caption' => 'Loved for travel days, daycare packing, and everything in between.',
],
[
'image' => asset('assets/images/Purees/mangy%20chewy%201.png'),
'handle' => '@snacktimewithtara',
'caption' => 'Parents approve the ingredient list. Kids approve the taste.',
],
];

$heroHighlights = [
[
'title' => 'Doctor-Founded',
'description' => 'Nutritional thinking shaped by real pediatric insight.',
],
[
'title' => 'Stage-Wise Choices',
'description' => 'Products built for each feeding milestone and age range.',
],
[
'title' => 'Clean-Label Convenience',
'description' => 'Transparent ingredients with easy delivery for busy families.',
],
];
@endphp

<section class="hero in-view relative isolate overflow-hidden px-4 pb-14 pt-12 sm:px-6 lg:px-8 lg:pb-20 lg:pt-16">
    <div class="mx-auto max-w-7xl">
        <article class="relative overflow-hidden rounded-3xl border border-white/40 bg-gradient-to-br from-[#fff9f2] via-white to-[#fff3e6] px-6 py-10 shadow-soft sm:px-10 sm:py-14 lg:px-14 lg:py-16">
            <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl sm:h-72 sm:w-72"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-orange-100/65 blur-3xl sm:h-72 sm:w-72"></div>

            <div class="relative z-10 max-w-2xl">
                <p class="mb-4 inline-flex rounded-full border border-numnam-200 bg-white/85 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-numnam-700">
                    NumNam Nutrition
                </p>
                <h1 class="text-balance text-3xl font-extrabold leading-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    {{ $homepageSections['hero_title'] ?? 'Shop clean-label, stage-wise baby nutrition made for modern families.' }}
                </h1>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-600 sm:mt-5 sm:text-lg">
                    {{ $homepageSections['hero_subtitle'] ?? 'NumNam offers age-appropriate baby foods, practical subscriptions, and ingredient transparency so parents can choose with confidence and feed with ease.' }}
                </p>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    @foreach($heroHighlights as $highlight)
                    <div class="rounded-2xl border border-white/70 bg-white/80 px-4 py-3 shadow-sm backdrop-blur-sm">
                        <p class="text-sm font-semibold text-slate-900">{{ $highlight['title'] }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-600">{{ $highlight['description'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-3 sm:mt-10">
                    <a class="hero-cta" href="{{ route('store.products') }}">Shop Products</a>
                    <a class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-300" href="{{ route('store.pricing') }}">Build Subscription</a>
                </div>
            </div>
        </article>
    </div>
</section>

<x-store.why-choose-us
    title="Why Choose Us"
    subtitle="Everything you need for confident shopping, from checkout to delivery."
    :benefits="$whyChooseUsBenefits" />

@if($topCategories->isNotEmpty())
<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Shop Top Categories</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">Browse our most-loved categories with dedicated landing pages and products tailored to every stage.</p>
            </div>
            <a href="{{ route('store.products') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900">View all products</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($topCategories as $category)
            <x-store.category-card :category="$category" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section ticker-strip">
    <div class="ticker-track">
        @php $tickerText = 'Fresh ingredients ★ Clean whole food ★ No preservatives ★ Stage-wise textures ★ Doctor founded ★ Subscription friendly ★ '; @endphp
        <p>{{ $tickerText }}</p>
        <p aria-hidden="true">{{ $tickerText }}</p>
    </div>
</section>

<section class="section trust-strip animate-fade-up">
    <h2>Why Parents Trust NumNam</h2>
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

{{-- Age Stage Navigator --}}
<section class="section age-stage-section animate-fade-up">
    <h2>Shop by Stage</h2>
    <p class="meta">Nutrition tailored to your baby's growth journey</p>
    <div class="age-stage-grid stagger-children">
        <a href="{{ route('store.products', ['age_group' => '4-6 Months']) }}" class="age-stage-card">
            <div class="age-stage-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M6 21v-2a4 4 0 014-4h4" />
                    <path d="M15 19l2 2 4-4" />
                </svg>
            </div>
            <h4>Stage 1</h4>
            <p class="meta">4–6 Months</p>
            <span class="age-stage-label">First Tastes</span>
        </a>
        <a href="{{ route('store.products', ['age_group' => '6-8 Months']) }}" class="age-stage-card">
            <div class="age-stage-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2a7 7 0 017 7c0 5-7 13-7 13S5 14 5 9a7 7 0 017-7z" />
                    <circle cx="12" cy="9" r="2.5" />
                </svg>
            </div>
            <h4>Stage 2</h4>
            <p class="meta">6–8 Months</p>
            <span class="age-stage-label">Exploring Flavours</span>
        </a>
        <a href="{{ route('store.products', ['age_group' => '8-12 Months']) }}" class="age-stage-card">
            <div class="age-stage-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87" />
                    <path d="M16 3.13a4 4 0 010 7.75" />
                </svg>
            </div>
            <h4>Stage 3</h4>
            <p class="meta">8–12 Months</p>
            <span class="age-stage-label">Textured Meals</span>
        </a>
        <a href="{{ route('store.products', ['age_group' => '12+ Months']) }}" class="age-stage-card">
            <div class="age-stage-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 8h1a4 4 0 010 8h-1" />
                    <path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z" />
                    <line x1="6" y1="1" x2="6" y2="4" />
                    <line x1="10" y1="1" x2="10" y2="4" />
                    <line x1="14" y1="1" x2="14" y2="4" />
                </svg>
            </div>
            <h4>Stage 4</h4>
            <p class="meta">12+ Months</p>
            <span class="age-stage-label">Family Foods</span>
        </a>
    </div>
</section>

<x-store.product-showcase
    title="Best Sellers"
    subtitle="The products families reorder most often for quality, convenience, and consistent results."
    :products="$bestSellerProducts"
    empty-text="Best sellers will appear here once orders start coming in." />

<x-store.product-showcase
    title="Featured Products"
    subtitle="Handpicked standout products from our latest collection, designed to make shopping easier."
    :products="$featuredProducts"
    empty-text="Featured products will appear here soon." />

@if(($recentlyViewedProducts ?? collect())->isNotEmpty())
<x-store.product-showcase
    title="Recently Viewed"
    subtitle="Pick up where you left off with the products you explored most recently."
    :products="$recentlyViewedProducts"
    empty-text="Your recently viewed products will appear here." />
@endif

{{-- How It Works --}}
<section class="section animate-fade-up">
    <h2>How It Works</h2>
    <p class="meta">Getting started is simple</p>
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

{{-- Ingredient Transparency --}}
<section class="section ingredient-section animate-fade-up">
    <div class="ingredient-grid">
        <div class="ingredient-copy">
            <span class="kicker">Transparency First</span>
            <h2>Know exactly what's inside</h2>
            <p class="meta">Every NumNam product lists its full ingredient deck — no hidden fillers, no mystery powders. Just clean, whole foods your baby deserves.</p>
            <ul class="ingredient-checklist">
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    100% natural, whole-food ingredients
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    Zero added sugar, salt or preservatives
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    European nutrition standards
                </li>
                <li>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--brand-1)" stroke-width="2.5">
                        <path d="M20 6L9 17l-5-5" />
                    </svg>
                    Stage-appropriate textures & nutrients
                </li>
            </ul>
            <a href="{{ route('store.about') }}" class="btn btn-secondary">Learn Our Story</a>
        </div>
        <div class="ingredient-visual">
            <div class="ingredient-badge-grid stagger-children">
                <div class="ingredient-badge">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 21c6 0 12-6 12-12V3h-6C6 3 3 6 3 12s3 9 3 9z" />
                    </svg>
                    <span>Organic Veggies</span>
                </div>
                <div class="ingredient-badge">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" />
                        <line x1="15" y1="9" x2="15.01" y2="9" />
                    </svg>
                    <span>No Artificial Flavours</span>
                </div>
                <div class="ingredient-badge">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2s6 7 6 11a6 6 0 11-12 0c0-4 6-11 6-11z" />
                    </svg>
                    <span>No Added Sugar</span>
                </div>
                <div class="ingredient-badge">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                    </svg>
                    <span>FSSAI Certified</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section animate-fade-up">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:32px;">
        <div>
            <h2 style="margin:0 0 4px;">Subscription Plans</h2>
            <p class="meta" style="margin:0;">Save more with regular deliveries — pause or cancel anytime.</p>
        </div>
        <a href="{{ route('store.pricing') }}" class="btn btn-secondary">View All Plans</a>
    </div>
    <div class="store-grid three stagger-children">
        @foreach($plans->take(3) as $plan)
        <article class="card" style="display:flex;flex-direction:column;">
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px;height:100%;">
                <h4 style="margin:0;">{{ $plan->name }}</h4>
                <p class="meta" style="margin:0;flex:1;">{{ Str::limit($plan->description, 100) }}</p>
                <div class="price" style="margin:4px 0;">
                    <strong style="font-size:26px;color:var(--brand-1);">Rs {{ number_format($plan->price, 0) }}</strong>
                    @if($plan->billing_cycle && $plan->billing_cycle !== 'one_time')
                    <span class="meta"> / {{ str_replace('_', ' ', $plan->billing_cycle) }}</span>
                    @endif
                </div>
                <a href="{{ route('store.pricing') }}" class="btn btn-primary" style="margin-top:auto;">Subscribe</a>
            </div>
        </article>
        @endforeach
    </div>
</section>

<x-store.user-generated-content
    title="See NumNam In Real Homes"
    subtitle="A clean, Instagram-style gallery using placeholder customer moments for now, ready to be replaced with real community content later."
    :items="$userGeneratedContent" />

<x-store.customer-reviews
    title="Customer Reviews"
    subtitle="Real feedback from parents who trust NumNam for everyday nutrition."
    :average-rating="$averageCustomerRating"
    :reviews="$customerReviews" />
@endsection