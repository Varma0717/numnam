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

<section class="hero-fullbleed in-view" style="background: linear-gradient(135deg, #1a0e14 0%, #2c1a2a 35%, #142030 70%, #0d1a14 100%);">
    {{-- Soft brand colour glow blobs --}}
    <div style="position:absolute;inset:0;pointer-events:none;overflow:hidden;">
        <div style="position:absolute;top:-8%;left:-5%;width:520px;height:520px;background:radial-gradient(circle,rgba(254,125,148,0.18) 0%,transparent 70%);border-radius:50%;"></div>
        <div style="position:absolute;bottom:-12%;right:18%;width:420px;height:420px;background:radial-gradient(circle,rgba(168,220,193,0.12) 0%,transparent 70%);border-radius:50%;"></div>
        <div style="position:absolute;top:15%;right:8%;width:300px;height:300px;background:radial-gradient(circle,rgba(252,93,77,0.10) 0%,transparent 70%);border-radius:50%;"></div>
    </div>

    {{-- Floating product images (right side, hidden on mobile) --}}
    <div class="pointer-events-none hidden md:block" style="position:absolute;right:0;top:0;bottom:0;width:44%;overflow:hidden;">
        <img src="{{ asset('assets/images/Puffs/Tikka%20Puffies/front.jpg') }}" alt="" loading="eager" style="position:absolute;top:8%;right:8%;height:55%;object-fit:contain;transform:rotate(-6deg);filter:drop-shadow(0 24px 64px rgba(0,0,0,0.5));opacity:0.95;">
        <img src="{{ asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg') }}" alt="" loading="eager" style="position:absolute;bottom:6%;right:30%;height:42%;object-fit:contain;transform:rotate(7deg);filter:drop-shadow(0 16px 48px rgba(0,0,0,0.4));opacity:0.85;">
        {{-- Gradient to fade images into bg on left edge --}}
        <div style="position:absolute;inset:0;background:linear-gradient(90deg,#1a0e14 0%,transparent 30%);"></div>
    </div>

    {{-- Hero content --}}
    <div class="hero-content">
        <div style="max-width:600px;">
            <p class="mb-4 inline-flex rounded-full border border-numnam-400/40 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-numnam-300 backdrop-blur-sm">
                NumNam Nutrition
            </p>
            <h1 class="text-balance text-3xl font-extrabold leading-tight text-white sm:text-4xl lg:text-5xl xl:text-6xl">
                {{ $homepageSections['hero_title'] ?? 'Clean-label, stage-wise baby nutrition for modern families.' }}
            </h1>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-slate-300 sm:mt-5 sm:text-lg">
                {{ $homepageSections['hero_subtitle'] ?? 'NumNam offers age-appropriate baby foods, practical subscriptions, and ingredient transparency so parents can choose with confidence and feed with ease.' }}
            </p>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                @foreach($heroHighlights as $highlight)
                <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-sm">
                    <p class="text-sm font-semibold text-white">{{ $highlight['title'] }}</p>
                    <p class="mt-1 text-xs leading-relaxed text-slate-400">{{ $highlight['description'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3 sm:mt-10">
                <a class="hero-cta" href="{{ route('store.products') }}">Shop Products</a>
                <a class="inline-flex items-center justify-center rounded-full border border-white/30 bg-white/10 px-6 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/40" href="{{ route('store.pricing') }}">Build Subscription</a>
            </div>
        </div>
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

<section class="px-4 py-10 sm:px-6 lg:px-8 lg:py-12">
    <div class="mx-auto grid max-w-7xl gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach($trustCards as $item)
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
            <h3 class="text-sm font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $item['title'] }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['subtitle'] }}</p>
        </article>
        @endforeach
    </div>
</section>

<section class="px-4 pb-4 sm:px-6 lg:px-8 lg:pb-6">
    <div class="mx-auto grid max-w-7xl gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <p class="text-3xl font-extrabold text-slate-900" data-count-to="10000">0+</p>
            <p class="mt-1 text-sm text-slate-600">Happy Families</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-slate-900" data-count-to="15">0+</p>
            <p class="mt-1 text-sm text-slate-600">Unique Products</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-slate-900" data-count-to="100">0%</p>
            <p class="mt-1 text-sm text-slate-600">Natural Ingredients</p>
        </div>
        <div>
            <p class="text-3xl font-extrabold text-slate-900" data-count-to="4">0.9â˜…</p>
            <p class="mt-1 text-sm text-slate-600">Average Rating</p>
        </div>
    </div>
</section>

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Shop by Stage</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">Nutrition tailored to your baby's growth journey.</p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('store.products', ['age' => '4-6']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-base font-semibold text-slate-900">Stage 1</h3>
                <p class="mt-1 text-sm text-slate-600">4â€“6 months</p>
                <p class="mt-3 text-xs font-medium uppercase tracking-[0.14em] text-numnam-700">First Tastes</p>
            </a>
            <a href="{{ route('store.products', ['age' => '6-8']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-base font-semibold text-slate-900">Stage 2</h3>
                <p class="mt-1 text-sm text-slate-600">6â€“8 months</p>
                <p class="mt-3 text-xs font-medium uppercase tracking-[0.14em] text-numnam-700">Exploring Flavours</p>
            </a>
            <a href="{{ route('store.products', ['age' => '8-12']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-base font-semibold text-slate-900">Stage 3</h3>
                <p class="mt-1 text-sm text-slate-600">8â€“12 months</p>
                <p class="mt-3 text-xs font-medium uppercase tracking-[0.14em] text-numnam-700">Textured Meals</p>
            </a>
            <a href="{{ route('store.products', ['age' => '12+']) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                <h3 class="text-base font-semibold text-slate-900">Stage 4</h3>
                <p class="mt-1 text-sm text-slate-600">12+ months</p>
                <p class="mt-3 text-xs font-medium uppercase tracking-[0.14em] text-numnam-700">Family Foods</p>
            </a>
        </div>
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

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
        <div class="mb-6">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">How It Works</h2>
            <p class="mt-2 text-sm text-slate-600">From stage selection to doorstep delivery, the process is simple and parent-friendly.</p>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-base font-semibold text-slate-900">Choose Your Stage</h3>
                <p class="mt-2 text-sm text-slate-600">Select products matched to your baby's age and developmental milestone.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-base font-semibold text-slate-900">We Prepare Fresh</h3>
                <p class="mt-2 text-sm text-slate-600">Every batch is crafted with clean ingredients and transparent nutrition labels.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="text-base font-semibold text-slate-900">Delivered To You</h3>
                <p class="mt-2 text-sm text-slate-600">Fast shipping and flexible subscriptions help families stay stocked without stress.</p>
            </div>
        </div>
    </div>
</section>

<section class="px-4 pb-8 sm:px-6 lg:px-8 lg:pb-10">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-gradient-to-br from-[#fff9f2] via-white to-[#fff4e8] p-6 shadow-soft sm:p-8 lg:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Subscription Plans</h2>
                <p class="mt-2 text-sm text-slate-600">Save more with regular deliveries. Pause or cancel anytime.</p>
            </div>
            <a href="{{ route('store.pricing') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900">View all plans</a>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            @foreach($plans->take(3) as $plan)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">{{ $plan->name }}</h3>
                <p class="mt-2 text-sm text-slate-600">{{ Str::limit($plan->description, 100) }}</p>
                <p class="mt-4 text-2xl font-bold text-slate-900">Rs {{ number_format($plan->price, 0) }}</p>
                @if($plan->billing_cycle && $plan->billing_cycle !== 'one_time')
                <p class="text-sm text-slate-500">/{{ str_replace('_', ' ', $plan->billing_cycle) }}</p>
                @endif
                <a href="{{ route('store.pricing') }}" class="hero-cta mt-5 w-full">Subscribe</a>
            </article>
            @endforeach
        </div>
    </div>
</section>

<x-store.user-generated-content
    title="Real Families, Real Moments"
    subtitle="Parents sharing their favourite NumNam feeding moments — from first tastes to favourite snack-time regulars."
    :items="$userGeneratedContent" />

<x-store.customer-reviews
    title="Customer Reviews"
    subtitle="Real feedback from parents who trust NumNam for everyday nutrition."
    :average-rating="$averageCustomerRating"
    :reviews="$customerReviews" />
@endsection