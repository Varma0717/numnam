@props([
'title',
'subtitle' => null,
'products' => collect(),
'emptyText' => 'No products available right now.',
])

<section class="px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
    <div class="mx-auto max-w-7xl">
        <div class="mb-6 max-w-2xl">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $title }}</h2>
            @if($subtitle)
            <p class="mt-2 text-sm leading-relaxed text-slate-600 sm:text-base">{{ $subtitle }}</p>
            @endif
        </div>

        @if($products->isEmpty())
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-center text-slate-600">{{ $emptyText }}</div>
        @else
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($products as $product)
            <x-store.product-card :product="$product" compact="true" />
            @endforeach
        </div>
        @endif
    </div>
</section>