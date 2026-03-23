@extends('store.layouts.app')

@section('title', 'NumNam - ' . $product->name)

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">{{ $product->age_group }} | {{ ucfirst($product->type) }}</span>
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->short_description ?: $product->description }}</p>
        <div class="price">
            <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
            @if($product->sale_price)
                <del>Rs {{ number_format($product->price, 0) }}</del>
            @endif
        </div>
        <form method="POST" action="{{ route('store.cart.add', $product) }}" class="store-actions">
            @csrf
            <input class="input" style="max-width:110px;" type="number" min="1" name="qty" value="1">
            <button class="cta-btn" type="submit">Add to cart</button>
            <a class="btn-soft" href="{{ route('store.checkout') }}">Buy Now</a>
        </form>
    </div>
    <div class="hero-art" style="background-image:url('{{ $product->image ?: '' }}'); background-size:cover;"></div>
</section>

@if($gallery->isNotEmpty())
<section class="section">
    <div class="section-head"><div><h3>Product Gallery</h3></div></div>
    <div class="store-grid three">
        @foreach($gallery as $photo)
            <article class="card">
                <div class="media" style="background-image:url('{{ $photo }}'); background-size:cover;"></div>
            </article>
        @endforeach
    </div>
</section>
@endif

<section class="section">
    <div class="section-head"><div><h3>Ingredients & Nutrition</h3></div></div>
    <div class="store-grid two">
        <article class="card">
            <div class="card-body">
                <h4>Ingredients</h4>
                <p class="meta">{{ $product->ingredients ?: 'Ingredient details will be updated soon.' }}</p>
            </div>
        </article>
        <article class="card">
            <div class="card-body">
                <h4>Nutrition Facts</h4>
                @php($nutrition = $product->nutrition_facts ?: $product->nutrition_info)
                @if(is_array($nutrition) && !empty($nutrition))
                    <ul>
                        @foreach($nutrition as $key => $value)
                            <li>{{ is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Nutrient' }}: {{ is_scalar($value) ? $value : json_encode($value) }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="meta">Detailed nutrition values are coming soon.</p>
                @endif
            </div>
        </article>
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Product Story</h3></div></div>
    <p class="meta">{{ $product->description }}</p>
</section>

@if($related->isNotEmpty())
<section class="section">
    <div class="section-head"><div><h3>Related products</h3></div></div>
    <div class="store-grid three">
        @foreach($related as $item)
            <article class="card">
                <div class="card-body">
                    <h4><a href="{{ route('store.product.show', $item) }}">{{ $item->name }}</a></h4>
                    <p class="meta">Rs {{ number_format($item->sale_price ?: $item->price, 0) }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection
