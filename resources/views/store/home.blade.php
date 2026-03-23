@extends('store.layouts.app')

@section('title', 'NumNam - Home')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Clean Nutrition Storefront</span>
        <h1>{{ $homepageSections['hero_title'] ?? 'Smart baby nutrition, delivered with parent-friendly convenience.' }}</h1>
        <p>{{ $homepageSections['hero_subtitle'] ?? 'Discover stage-wise foods, subscriptions, and transparent ingredients built for modern families.' }}</p>
        <div class="hero-actions">
            <a class="cta-btn" href="{{ route('store.products') }}">Shop Products</a>
            <a class="btn-soft" href="{{ route('store.pricing') }}">Build Subscription</a>
        </div>
        <div class="hero-metrics">
            <div class="metric"><strong>50k+</strong><span>Meals served monthly</span></div>
            <div class="metric"><strong>98%</strong><span>Repeat purchase rate</span></div>
            <div class="metric"><strong>12+</strong><span>Age-stage formats</span></div>
            <div class="metric"><strong>24h</strong><span>Fast dispatch window</span></div>
        </div>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section ticker-strip">
    <p>Fresh ingredients | Clean whole food | No preservatives | Stage-wise textures | Doctor founded | Subscription friendly</p>
</section>

<section class="section">
    <div class="section-head"><div><h3>Shop by Category</h3></div></div>
    <div class="store-grid three">
        @forelse($topCategories as $category)
            <article class="card">
                <div class="card-body">
                    <span class="kicker">{{ $category->products_count }} products</span>
                    <h4>{{ $category->name }}</h4>
                    <p class="meta">Explore curated products for this feeding stage.</p>
                    <div class="store-actions">
                        <a class="btn-soft" href="{{ route('store.products', ['category' => $category->slug]) }}">Explore</a>
                    </div>
                </div>
            </article>
        @empty
            <p class="meta">Categories will appear here once published.</p>
        @endforelse
    </div>
</section>

<section class="section trust-strip">
    <div class="section-head"><div><h3>Why Parents Choose NumNam</h3></div></div>
    <div class="store-grid two">
        @foreach($trustHighlights as $item)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $item['title'] }}</h4>
                    <p class="meta">{{ $item['text'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Featured Products</h3></div></div>
    <div class="store-grid three">
        @forelse($featuredProducts as $product)
            <article class="card">
                <div class="media" style="background-image:url('{{ $product->image ?: '' }}'); background-size:cover;"></div>
                <div class="card-body">
                    <span class="kicker">{{ $product->age_group }}</span>
                    <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 90) }}</p>
                    <div class="price">
                        <strong>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                        @if($product->sale_price)
                            <del>Rs {{ number_format($product->price, 0) }}</del>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('store.cart.add', $product) }}" class="store-actions">
                        @csrf
                        <button class="btn-primary" type="submit">Add to Cart</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="meta">No featured products yet.</p>
        @endforelse
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Subscription Plans</h3></div></div>
    <div class="store-grid three">
        @foreach($plans as $plan)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $plan->name }}</h4>
                    <p class="meta">{{ $plan->description }}</p>
                    <div class="price"><strong>Rs {{ number_format($plan->price, 0) }}</strong></div>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Parent Stories</h3></div></div>
    <div class="store-grid three">
        @foreach($testimonials as $testimonial)
            <article class="card testimonial-card">
                <div class="card-body">
                    <p>"{{ $testimonial['quote'] }}"</p>
                    <p class="meta"><strong>{{ $testimonial['name'] }}</strong></p>
                </div>
            </article>
        @endforeach
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>#Ask Moms Community</h3></div></div>
    <p class="meta">Connect with parents, discover practical feeding tips, and explore fresh articles from our learning corner.</p>
    <div class="hero-actions">
        <a class="cta-btn" href="{{ route('store.blog.index') }}">Read Blog</a>
        <a class="btn-soft" href="{{ route('store.contact') }}">Join Community</a>
    </div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Latest Guides</h3></div></div>
    <div class="store-grid three">
        @foreach($latestBlogs as $blog)
            <article class="card">
                <div class="card-body">
                    <h4><a href="{{ route('store.blog.show', $blog) }}">{{ $blog->title }}</a></h4>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($blog->excerpt, 120) }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
