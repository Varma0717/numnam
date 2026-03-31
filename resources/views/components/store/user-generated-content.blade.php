@props([
'title' => 'Loved By Real Families',
'subtitle' => 'A glimpse of how NumNam fits into real mealtime routines, from first tastes to everyday family moments.',
'items' => [],
])

@php
$galleryItems = collect($items)->take(6);
@endphp

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 lg:p-10">
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full border border-pink-200 bg-pink-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-pink-700">User Generated Content</span>
                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $subtitle }}</p>
            </div>
            <a href="https://www.instagram.com/numnam_baby" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900">Follow on Instagram</a>
        </div>

        <div class="grid auto-rows-[180px] gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($galleryItems as $item)
            @php
            $articleClasses = 'group relative overflow-hidden rounded-3xl bg-slate-100 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md';
            if ($loop->first) {
            $articleClasses .= ' lg:col-span-2 lg:row-span-2';
            }
            if ($loop->index === 4) {
            $articleClasses .= ' lg:row-span-2';
            }
            $imageUrl = $item['image'] ?? '';
            @endphp
            <article class="{{ $articleClasses }}">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-500 group-hover:scale-105" style="background-image: url('{{ $imageUrl }}');"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-900/15 to-transparent"></div>
                <div class="relative flex h-full flex-col justify-end p-4 text-white sm:p-5">
                    <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium backdrop-blur-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        <span>{{ $item['handle'] ?? '@numnam_family' }}</span>
                    </div>
                    <p class="mt-3 text-sm font-semibold leading-relaxed sm:text-base">{{ $item['caption'] ?? '' }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>