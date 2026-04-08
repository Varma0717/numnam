@extends('store.layouts.app')

@section('title', 'NumNam - About Us')
@section('meta_description', 'Learn about NumNam, our brand story, mission, vision, and the trust we have built with families seeking clean-label baby nutrition.')

@php
$trustMetrics = [
[
'value' => '3+',
'label' => 'Years Building Better Nutrition',
],
[
'value' => '10,000+',
'label' => 'Families Served',
],
[
'value' => '15+',
'label' => 'Stage-Wise Products',
],
[
'value' => '4.8/5',
'label' => 'Average Parent Rating',
],
];

$brandPillars = [
[
'title' => 'Brand Story',
'description' => 'NumNam started with a simple question: why should parents have to choose between convenience and trust when feeding their children?',
],
[
'title' => 'Mission',
'description' => 'To make stage-appropriate, clean-label baby nutrition easier to access, easier to understand, and easier to trust.',
],
[
'title' => 'Vision',
'description' => 'To become the most trusted nutrition partner for modern families from first tastes to confident self-feeding.',
],
];

$trustHighlights = [
[
'title' => 'Clinical Thinking',
'description' => 'Guided by pediatric and nutrition expertise so every product supports the needs of growing children.',
'icon' => 'shield',
],
[
'title' => 'Clean Ingredient Standards',
'description' => 'No unnecessary additives, no hidden fillers, and transparent labels that parents can read in seconds.',
'icon' => 'leaf',
],
[
'title' => 'Built For Real Families',
'description' => 'Designed for busy schedules, changing routines, and the realities of everyday parenting.',
'icon' => 'heart',
],
];
@endphp

@section('structured_data')
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AboutPage",
        "name": "About NumNam",
        "url": "{{ route('store.about') }}",
        "description": "Learn about NumNam, our story, mission, vision, and approach to clean-label baby nutrition.",
        "mainEntity": {
            "@type": "Organization",
            "name": "NumNam",
            "url": "{{ url('/') }}",
            "email": "info@numnam.com",
            "telephone": "+91-9014252278"
        }
    }
</script>
@endsection

@section('content')
<section class="px-4 py-10 sm:px-6 lg:px-8 lg:py-14">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-14 lg:py-14" style="border-color:#FFD6E5;">
            <div class="max-w-3xl">
                <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-numnam-700">About NumNam</span>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">From clinical insight to a trusted everyday feeding brand</h1>
                <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600 sm:text-lg">NumNam was built to give modern families a more confident way to feed their little ones: clean-label products, transparent ingredients, and practical formats shaped by real parenting needs.</p>
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($trustMetrics as $metric)
                <article class="rounded-2xl border border-white/70 bg-white/90 p-5 shadow-sm">
                    <p class="text-2xl font-bold text-slate-900 sm:text-3xl">{{ $metric['value'] }}</p>
                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $metric['label'] }}</p>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-start">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Our Brand Story</h2>
            <p class="mt-4 text-base leading-relaxed text-slate-600">NumNam began with a problem our founders knew personally: many parents want food that is genuinely nutritious and transparent, but the market often forces a compromise between quality, convenience, and trust. We believed there had to be a better standard.</p>
            <p class="mt-4 text-base leading-relaxed text-slate-600">So we built NumNam around three simple principles: ingredient clarity, stage-appropriate nutrition, and parent-friendly convenience. Every product is designed to reduce decision fatigue and bring more confidence to mealtime.</p>
            <p class="mt-4 text-base leading-relaxed text-slate-600">Today, NumNam continues to grow with families who want thoughtfully made products backed by real nutritional intent rather than marketing shortcuts.</p>
        </article>

        <div class="grid gap-4">
            @foreach($brandPillars as $pillar)
            <article class="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">{{ $pillar['title'] }}</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $pillar['description'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
        <div class="max-w-2xl">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Why Families Trust NumNam</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">Our approach combines nutritional rigor with practical product design so parents get clarity, consistency, and confidence in every order.</p>
        </div>

        <div class="mt-8 grid gap-4 lg:grid-cols-3">
            @foreach($trustHighlights as $item)
            <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-numnam-200 hover:bg-white hover:shadow-md">
                <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl border border-numnam-200 bg-white text-numnam-700" aria-hidden="true">
                    @switch($item['icon'])
                    @case('shield')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                    </svg>
                    @break
                    @case('leaf')
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 21c6 0 12-6 12-12V3h-6C6 3 3 6 3 12s3 9 3 9z" />
                    </svg>
                    @break
                    @default
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    @endswitch
                </span>
                <h3 class="text-lg font-semibold text-slate-900">{{ $item['title'] }}</h3>
                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['description'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Meet The Founders</h2>
        <p class="mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">NumNam is guided by people who understand both the science of nutrition and the realities of modern parenting.</p>

        <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($founders as $founder)
            <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-6 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-md">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-numnam-600 text-sm font-bold text-white">{{ strtoupper(substr($founder['name'], 0, 1) . substr(strstr($founder['name'], ' '), 1, 1)) }}</div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $founder['name'] }}</h3>
                        <p class="mt-1 inline-flex rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-numnam-700">{{ $founder['role'] }}</p>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $founder['bio'] }}</p>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
</section>

<section class="px-4 pb-14 pt-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-slate-900 px-6 py-8 text-white shadow-soft sm:px-8 lg:flex lg:items-center lg:justify-between lg:gap-8">
        <div class="max-w-2xl">
            <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Building trust into every stage of feeding</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-300 sm:text-base">Explore stage-wise products developed to bring more confidence, clarity, and convenience to your family routine.</p>
        </div>
        <div class="mt-6 flex flex-wrap gap-3 lg:mt-0">
            <a class="hero-cta" href="{{ route('store.products') }}">Shop Products</a>
            <a class="inline-flex items-center justify-center rounded-full border border-white/25 px-6 py-3 text-sm font-semibold text-white transition-all duration-300 hover:-translate-y-0.5 hover:border-white/45 hover:bg-white/10" href="{{ route('store.contact') }}">Get in Touch</a>
        </div>
    </div>
</section>
@endsection