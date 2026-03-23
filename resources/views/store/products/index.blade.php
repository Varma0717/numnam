@extends('store.layouts.app')

@section('title', 'NumNam - Products')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Shop</span>
        <h1>All Products</h1>
        <p>Explore fruitie packs, upcoming puffs, and stage-based nutrition options with quick filters.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section catalog-filters">
    <form method="GET" class="store-grid two">
        <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Search products">
        <select class="input" name="category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select class="input" name="type">
            <option value="">All types</option>
            @foreach(['puree', 'puffs', 'cookies'] as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
        <button class="cta-btn" type="submit">Apply filters</button>
    </form>
</section>

<section class="section">
    <div class="store-grid three">
        @forelse($products as $product)
            <article class="card">
                <div class="media" style="background-image:url('{{ $product->image ?: '' }}'); background-size:cover;"></div>
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
                        <button class="btn-primary" type="submit">Add to cart</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="meta">No products found.</p>
        @endforelse
    </div>

    <div class="pager">{{ $products->links() }}</div>
</section>
@endsection
