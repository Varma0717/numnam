@extends('store.layouts.app')

@section('title', 'NumNam - Products')

@section('content')
@php
$productPlaceholders = [
asset('assets/images/product_1.png'),
asset('assets/images/product_2.png'),
asset('assets/images/product_3.png'),
asset('assets/images/product_4.png'),
];
@endphp

<section class="hero section in-view">
    <div>
        <span class="kicker">Shop</span>
        <h1>All Products</h1>
        <p>Explore fruitie packs, upcoming puffs, and stage-based nutrition options with quick filters.</p>
    </div>
</section>

<section class="section catalog-filters">
    <form method="GET" class="catalog-filter-bar">
        <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Search products...">
        <select class="input" name="category">
            <option value="">All categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="input" name="type">
            <option value="">All types</option>
            @foreach(['puree', 'puffs', 'cookies'] as $type)
            <option value="{{ $type }}" @selected(request('type')===$type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <button class="cta-btn" type="submit">Apply Filters</button>
    </form>
</section>

<section class="section">
    <div class="catalog-sort">
        <span class="result-count">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found</span>
        <select class="input" name="sort" onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(request('sort', 'newest' )==='newest' )>Newest First</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" @selected(request('sort')==='price_low' )>Price: Low to High</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" @selected(request('sort')==='price_high' )>Price: High to Low</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_az']) }}" @selected(request('sort')==='name_az' )>Name: A-Z</option>
        </select>
    </div>

    <div class="store-grid three stagger-children">
        @forelse($products as $product)
        @php($placeholderImage = $productPlaceholders[$loop->index % count($productPlaceholders)])
        <article class="card hover-up animate-fade-up" style="--stagger-delay: {{ $loop->index * 80 }}ms">
            <div class="media" style="background-image:url('{{ $placeholderImage }}'); background-size:cover;">
                @if($product->sale_price)
                <span class="badge-sale">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                @endif
                @if($product->created_at->gt(now()->subDays(14)))
                <span class="badge-new">New</span>
                @endif
            </div>
            <div class="card-body">
                <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                <p class="meta">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 100) }}</p>
                <div class="price">
                    <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                    @if($product->sale_price)
                    <del>Rs {{ number_format($product->price, 0) }}</del>
                    @endif
                </div>
                <form method="POST" action="{{ route('store.cart.add', $product) }}">
                    @csrf
                    <button class="btn-primary" type="submit">Add to Cart</button>
                </form>
            </div>
        </article>
        @empty
        <div class="empty-state" style="grid-column: 1 / -1;">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--line)" stroke-width="1.5" style="margin: 0 auto 16px;">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3>No products found</h3>
            <p class="meta">Try adjusting your filters or search terms.</p>
            <a class="cta-btn" href="{{ route('store.products') }}">View All Products</a>
        </div>
        @endforelse
    </div>

    <div class="pager">{{ $products->links() }}</div>
</section>
@endsection