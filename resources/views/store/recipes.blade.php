@extends('store.layouts.app')

@section('title', 'NumNam - Recipes & Nutrition Tips')
@section('meta_description', 'Simple, stage-aware recipes and feeding tips for infants and toddlers.')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fff5f8] via-white to-[#eef9f6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-pastel-mint/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Recipes</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Infant nutrition recipes and practical feeding tips</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Simple, stage-aware ideas that support weaning and healthy mealtime habits.</p>
        </div>
    </div>
</section>

<section class="section pb-8">
    <h2 class="mb-5 text-xl font-extrabold text-slate-900">Core Feeding Tips</h2>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @foreach($recipeTips as $index => $tip)
        <article class="flex gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-numnam-100 text-sm font-bold text-numnam-700">{{ $index + 1 }}</span>
            <p class="text-sm leading-relaxed text-slate-600">{{ $tip }}</p>
        </article>
        @endforeach
    </div>
</section>

<section class="section pb-8">
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 bg-slate-50/70 px-7 py-5">
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Beginner Recipe</span>
            <h3 class="mt-2 text-xl font-bold text-slate-900">Carrot Puree Starter (6M+)</h3>
            <div class="mt-2 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    15 mins
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l1.9 4.9L19 9l-5.1 2.1L12 16l-1.9-4.9L5 9l5.1-2.1L12 2z" />
                    </svg>
                    Easy
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    6M+
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-0 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0 px-0">
            <div class="px-7 py-6">
                <h4 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500">Ingredients</h4>
                <ul class="space-y-2 text-sm leading-relaxed text-slate-600">
                    <li class="flex items-start gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-numnam-400"></span>1 medium carrot</li>
                    <li class="flex items-start gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-numnam-400"></span>Filtered water</li>
                    <li class="flex items-start gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-numnam-400"></span>Tiny drop of cold-pressed oil (optional)</li>
                </ul>
            </div>
            <div class="px-7 py-6">
                <h4 class="mb-3 text-sm font-bold uppercase tracking-wider text-slate-500">Method</h4>
                <ol class="space-y-2 text-sm leading-relaxed text-slate-600">
                    <li class="flex gap-3"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-numnam-100 text-xs font-bold text-numnam-700">1</span>Peel and chop carrot into small chunks.</li>
                    <li class="flex gap-3"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-numnam-100 text-xs font-bold text-numnam-700">2</span>Steam until very soft (about 10 minutes).</li>
                    <li class="flex gap-3"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-numnam-100 text-xs font-bold text-numnam-700">3</span>Blend with a little water until smooth.</li>
                    <li class="flex gap-3"><span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-numnam-100 text-xs font-bold text-numnam-700">4</span>Serve warm and fresh â€” spoon-friendly consistency.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="section pb-14">
    <div class="mb-5 flex items-center justify-between">
        <h3 class="text-xl font-extrabold text-slate-900">Latest From Blog</h3>
    </div>
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($featuredArticles as $article)
        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <a class="block aspect-[4/3] overflow-hidden" href="{{ route('store.blog.show', $article) }}">
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    src="{{ $article->featured_image ?: asset('assets/images/background_img.jpg') }}"
                    alt="{{ $article->title }}" loading="lazy">
            </a>
            @if($article->category)
            <div class="px-5 pt-4">
                <span class="inline-flex rounded-full border border-numnam-200 bg-numnam-50 px-2.5 py-0.5 text-xs font-semibold text-numnam-700">{{ $article->category->name }}</span>
            </div>
            @endif
            <div class="px-5 pb-6 pt-3">
                <p class="text-xs text-slate-400">{{ optional($article->published_at)->format('d M Y') }}</p>
                <h4 class="mt-1 text-base font-bold leading-snug text-slate-900">
                    <a class="hover:text-numnam-700" href="{{ route('store.blog.show', $article) }}">{{ $article->title }}</a>
                </h4>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ \Illuminate\Support\Str::limit($article->excerpt, 110) }}</p>
                <a class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-numnam-700 hover:underline" href="{{ route('store.blog.show', $article) }}">Read More &rarr;</a>
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-slate-500">No recipe articles yet.</p>
        </div>
        @endforelse
    </div>

    @if($featuredArticles->isNotEmpty())
    <div class="mt-8 text-center">
        <a class="inline-flex h-10 items-center rounded-full border border-slate-200 bg-white px-6 text-sm font-medium text-slate-600 transition hover:bg-slate-50" href="{{ route('store.blog.index') }}">View All Articles &rarr;</a>
    </div>
    @endif
</section>
@endsection
</section>
@endsection
