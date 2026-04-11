@extends('store.layouts.app')

@section('title', 'NumNam - Products')

@section('content')
@php
$productPlaceholders = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Purees/appi%20pooch%202.png'),
asset('assets/images/Purees/berry%20swush%202.png'),
];
@endphp

<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border-2 border-[#FFD93D] bg-white px-3 py-1 font-heading text-xs font-bold uppercase tracking-widest" style="color:#FF6B8A;">Shop NumNam</span>
            <h1 class="mt-4 font-heading text-3xl font-bold tracking-tight sm:text-4xl" style="color:#2D2D3F;">Wholesome Baby Food</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed" style="color:#5e6478;">Stage-based nutrition made from real ingredients, from smooth purees to playful puffs for every milestone.</p>
        </div>
    </div>
</section>

{{-- Age-stage quick filter chips --}}
<section class="section py-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">Filter by age</p>
    </div>
    <div class="mt-3 flex flex-wrap gap-2.5">
        <a href="{{ route('store.products', array_merge(request()->except('age','page'), request('age') === '4-6' ? [] : ['age' => '4-6'])) }}"
            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-all duration-200 {{ request('age') === '4-6' ? 'border-numnam-200 bg-numnam-50 text-numnam-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:text-slate-900' }}">
            <span aria-hidden="true">&#x1F34C;</span> 4-6 months
        </a>
        <a href="{{ route('store.products', array_merge(request()->except('age','page'), request('age') === '6-8' ? [] : ['age' => '6-8'])) }}"
            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-all duration-200 {{ request('age') === '6-8' ? 'border-numnam-200 bg-numnam-50 text-numnam-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:text-slate-900' }}">
            <span aria-hidden="true">&#x1F955;</span> 6-8 months
        </a>
        <a href="{{ route('store.products', array_merge(request()->except('age','page'), request('age') === '8-12' ? [] : ['age' => '8-12'])) }}"
            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-all duration-200 {{ request('age') === '8-12' ? 'border-numnam-200 bg-numnam-50 text-numnam-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:text-slate-900' }}">
            <span aria-hidden="true">&#x1F966;</span> 8-12 months
        </a>
        <a href="{{ route('store.products', array_merge(request()->except('age','page'), request('age') === '12+' ? [] : ['age' => '12+'])) }}"
            class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-all duration-200 {{ request('age') === '12+' ? 'border-numnam-200 bg-numnam-50 text-numnam-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:text-slate-900' }}">
            <span aria-hidden="true">&#x1F34E;</span> 12+ months
        </a>
    </div>
</section>

<section class="section py-6">
    <form method="GET" class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-4">
                <label for="catalog-q" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Search</label>
                <input id="catalog-q" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" type="text" name="q" value="{{ request('q') }}" placeholder="Search products...">
            </div>

            @if(request('age'))<input type="hidden" name="age" value="{{ request('age') }}">@endif

            <div class="lg:col-span-3">
                <label for="catalog-category" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Category</label>
                <select id="catalog-category" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label for="catalog-type" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Type</label>
                <select id="catalog-type" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" name="type">
                    <option value="">All types</option>
                    @foreach(['puree', 'puffs', 'cookies'] as $type)
                    <option value="{{ $type }}" @selected(request('type')===$type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button class="inline-flex h-11 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">Apply Filters</button>
            </div>
        </div>
    </form>
</section>

<section class="section">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <span class="text-sm font-medium text-slate-600">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found</span>
        <select class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400 sm:w-auto" name="sort" onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(request('sort', 'newest' )==='newest' )>Newest First</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" @selected(request('sort')==='price_low' )>Price: Low to High</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" @selected(request('sort')==='price_high' )>Price: High to Low</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_az']) }}" @selected(request('sort')==='name_az' )>Name: A-Z</option>
        </select>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($products as $product)
        @php($productImage = $product->image_url ?: $productPlaceholders[$loop->index % count($productPlaceholders)])
        <article class="group overflow-hidden rounded-[2rem] border-3 bg-white transition-transform duration-200 hover:-translate-y-1" style="border-color:#FFD6E5;">
            <a href="{{ route('store.product.show', $product) }}" class="block">
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100" style="background-image:url('{{ $productImage }}'); background-size:cover; background-position:center;">
                    @if($product->sale_price)
                    <span class="absolute left-3 top-3 inline-flex rounded-full bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                    @endif
                    @if($product->created_at->gt(now()->subDays(14)))
                    <span class="absolute right-3 top-3 inline-flex rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white">New</span>
                    @endif
                </div>
            </a>
            <div class="p-5 sm:p-6">
                @if($product->age_group)
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $product->age_group }}</p>
                @endif

                <h3 class="mt-2 text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700">
                    <a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 100) }}</p>

                <div class="mt-4 flex items-center gap-2">
                    <strong class="text-lg text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                    @if($product->sale_price)
                    <del class="text-sm text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                    @endif
                </div>

                <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-4">
                    @csrf
                    <button class="inline-flex h-10 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">Add to Cart</button>
                </form>

                <x-store.social-proof customers="10,000+" rating="4.8" compact="true" />
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-4 text-slate-300">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3 class="text-xl font-semibold text-slate-900">No products found</h3>
            <p class="mt-2 text-sm text-slate-600">Try adjusting your filters or search terms.</p>
            <a class="mt-5 inline-flex items-center justify-center rounded-full bg-numnam-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" href="{{ route('store.products') }}">View All Products</a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
</section>
@endsection