@props([
'category',
'image' => null,
'showCount' => true,
])

@php
$fallbackImages = [
asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'),
asset('assets/images/Purees/mangy%20chewy%201.png'),
asset('assets/images/Purees/brocco%20pop%201.png'),
];

$resolvedImage = $image ?: ($category->image ?: $fallbackImages[$category->id % count($fallbackImages)]);
@endphp

<article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
    <a href="{{ route('store.category.show', $category) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden bg-slate-100" style="background-image:url('{{ $resolvedImage }}'); background-size:cover; background-position:center;"></div>
        <div class="p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700">{{ $category->name }}</h3>
                    @if($showCount)
                    <p class="mt-1 text-sm text-slate-600">{{ $category->products_count ?? $category->products()->where('is_active', true)->count() }} products</p>
                    @endif
                </div>
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-numnam-50 text-numnam-700 transition-colors duration-200 group-hover:bg-numnam-100" aria-hidden="true">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </span>
            </div>
        </div>
    </a>
</article>