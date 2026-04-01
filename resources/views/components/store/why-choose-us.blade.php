@props([
'title' => 'Why Choose Us',
'subtitle' => 'Designed for a smoother, safer shopping experience every time.',
'benefits' => [],
])

@php
$items = !empty($benefits) ? $benefits : [
[
'icon' => 'truck',
'title' => 'Fast Delivery',
'description' => 'Quick dispatch and reliable shipping so your essentials arrive on time.',
],
[
'icon' => 'lock',
'title' => 'Secure Payment',
'description' => 'Trusted checkout with encrypted transactions to keep your data protected.',
],
[
'icon' => 'badge',
'title' => 'Quality Products',
'description' => 'Carefully selected products with strict quality standards and clean sourcing.',
],
[
'icon' => 'refresh',
'title' => 'Easy Returns',
'description' => 'Simple return process and responsive support whenever you need assistance.',
],
];
@endphp

<section class="px-4 pb-10 pt-4 sm:px-6 lg:px-8 lg:pb-14">
    <div class="mx-auto max-w-7xl">
        <div class="rounded-3xl border border-slate-200/80 bg-white px-6 py-8 shadow-sm sm:px-8 sm:py-10 lg:px-10">
            <div class="grid gap-8 lg:grid-cols-[300px_minmax(0,1fr)] lg:items-center lg:gap-10">
                <div class="why-choose-media" aria-hidden="true">
                    <div class="why-choose-media-ring">
                        <img src="{{ asset('assets/images/0-18months.webp') }}" alt="" loading="lazy" class="why-choose-media-image">
                    </div>
                </div>

                <div>
                    <div class="mx-auto max-w-2xl text-center lg:mx-0 lg:text-left">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $subtitle }}</p>
                    </div>

                    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:mt-8">
                        @foreach($items as $benefit)
                        <article class="group rounded-2xl border border-slate-200 bg-slate-50/70 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-numnam-200 hover:bg-white hover:shadow-md">
                            <div class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl border border-numnam-200/70 bg-white text-numnam-700 transition-colors duration-300 group-hover:bg-numnam-50" aria-hidden="true">
                                @switch($benefit['icon'] ?? '')
                                @case('truck')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M3 7h11v8H3z" />
                                    <path d="M14 10h3l4 3v2h-7" />
                                    <circle cx="7" cy="17" r="2" />
                                    <circle cx="17" cy="17" r="2" />
                                </svg>
                                @break
                                @case('lock')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="4" y="10" width="16" height="10" rx="2" />
                                    <path d="M8 10V8a4 4 0 118 0v2" />
                                </svg>
                                @break
                                @case('badge')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 3l2.8 1.3 3.1-.2.6 3 2.1 2.2-2.1 2.2-.6 3-3.1-.2L12 16l-2.8-1.3-3.1.2-.6-3L3.4 9.7l2.1-2.2.6-3 3.1.2L12 3z" />
                                    <path d="M9.5 12l1.7 1.7L14.8 10" />
                                </svg>
                                @break
                                @default
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M21 12a9 9 0 11-2.64-6.36" />
                                    <path d="M21 3v6h-6" />
                                </svg>
                                @endswitch
                            </div>

                            <h3 class="text-base font-semibold text-slate-900">{{ $benefit['title'] ?? '' }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $benefit['description'] ?? '' }}</p>
                        </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>