@props([
'title' => 'What Parents Say',
'subtitle' => 'Trusted by families who choose NumNam every week.',
'averageRating' => 4.8,
'reviews' => [],
])

@php
$items = !empty($reviews) ? $reviews : [
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
'comment' => 'Great variety and transparent labels. Support team is also quick to help when we have questions.',
],
[
'name' => 'Karan & Nisha',
'rating' => 5,
'comment' => 'We switched to NumNam recently and saw a big improvement in our little one\'s mealtime routine.',
],
];

$avg = number_format((float) $averageRating, 1);
@endphp

<section class="px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <div class="mx-auto max-w-7xl">
        <div class="rounded-3xl border border-slate-200 bg-white px-6 py-8 shadow-sm sm:px-8 lg:px-10 lg:py-10">
            <div class="flex flex-col gap-6 border-b border-slate-200 pb-7 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $subtitle }}</p>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 sm:text-right">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Average Rating</p>
                    <div class="mt-1 flex items-center gap-2 sm:justify-end">
                        <span class="text-2xl font-bold text-slate-900">{{ $avg }}/5</span>
                        <div class="flex items-center" aria-label="Average rating {{ $avg }} out of 5">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= round((float)$averageRating) ? 'text-amber-400' : 'text-amber-200' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.08 3.32a1 1 0 00.95.69h3.49c.969 0 1.371 1.24.588 1.81l-2.824 2.052a1 1 0 00-.364 1.118l1.079 3.321c.3.921-.755 1.688-1.54 1.118l-2.824-2.052a1 1 0 00-1.176 0l-2.824 2.052c-.784.57-1.838-.197-1.539-1.118l1.08-3.32a1 1 0 00-.365-1.119L2.98 8.747c-.783-.57-.38-1.81.588-1.81h3.49a1 1 0 00.951-.69l1.04-3.32z" />
                                </svg>
                                @endfor
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-7 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach($items as $review)
                <article class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5 transition-all duration-300 hover:-translate-y-1 hover:border-amber-200 hover:bg-white hover:shadow-md">
                    <div class="mb-3 flex items-center gap-1" aria-label="Rating {{ $review['rating'] ?? 5 }} out of 5">
                        @for($star = 1; $star <= 5; $star++)
                            <svg class="h-4 w-4 {{ $star <= ($review['rating'] ?? 5) ? 'text-amber-400' : 'text-slate-300' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.08 3.32a1 1 0 00.95.69h3.49c.969 0 1.371 1.24.588 1.81l-2.824 2.052a1 1 0 00-.364 1.118l1.079 3.321c.3.921-.755 1.688-1.54 1.118l-2.824-2.052a1 1 0 00-1.176 0l-2.824 2.052c-.784.57-1.838-.197-1.539-1.118l1.08-3.32a1 1 0 00-.365-1.119L2.98 8.747c-.783-.57-.38-1.81.588-1.81h3.49a1 1 0 00.951-.69l1.04-3.32z" />
                            </svg>
                            @endfor
                    </div>

                    <p class="text-sm leading-relaxed text-slate-600">&ldquo;{{ $review['comment'] ?? '' }}&rdquo;</p>

                    <p class="mt-4 text-sm font-semibold text-slate-900">{{ $review['name'] ?? 'Verified Parent' }}</p>
                </article>
                @endforeach
            </div>
        </div>
    </div>
</section>