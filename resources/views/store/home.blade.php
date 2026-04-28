@extends('store.layouts.app')

@section('title', 'NumNam - Vegetable Rich Baby Food | Doctor-Founded')
@section('meta_description', 'Doctor-founded baby food inspired by European nutrition standards. Vegetable-rich, no added sugars, no preservatives. Real fruits and vegetables for your little one.')

@section('content')
@php
$pureeItems = [
['img' => asset('assets/images/Purees/brocco%20pop%201.png'), 'name' => 'Brocco Pop', 'slug' => 'brocco-pop'],
['img' => asset('assets/images/Purees/berry%20swush%201.png'), 'name' => 'Berry Swush', 'slug' => 'berry-swush'],
['img' => asset('assets/images/Purees/mangy%20chewy%201.png'), 'name' => 'Mangy Chewy', 'slug' => 'mangy-chewy'],
['img' => asset('assets/images/Purees/appi%20pooch%201.png'), 'name' => 'Appi Pooch', 'slug' => 'appi-pooch'],
];

$puffItems = [
['img' => asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'), 'name' => 'Manchurian Munchos', 'slug' => 'manchurian-munchos'],
['img' => asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'), 'name' => 'Tikka Puffies', 'slug' => 'tikka-puffies'],
['img' => asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'), 'name' => 'Tomaty Pumpos', 'slug' => 'tomaty-pumpos'],
['img' => asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'), 'name' => 'Cheezy Bubbles', 'slug' => 'cheezy-bubbles'],
];

// Front-image-only lists for homepage products section
$pureeGalleryItems = $pureeItems;
$puffGalleryItems = $puffItems;

$favItems = [
$pureeItems[1],
$puffItems[0],
$puffItems[3],
];

$trustItems = [
['title' => 'Doctor-Founded', 'caption' => 'Built by doctor-parents for real family feeding journeys.', 'icon' => 'doctor'],
['title' => 'Vegetable Forward', 'caption' => 'Early flavor learning with practical vegetable-rich recipes.', 'icon' => 'veg'],
['title' => 'No added sugar', 'caption' => 'Balanced taste without unnecessary sweetness.', 'icon' => 'sugar'],
['title' => 'No preservatives', 'caption' => 'Clean-label food made to stay parent-friendly and simple.', 'icon' => 'preservatives'],
];

$blockCards = [
[
'eyebrow' => 'Parent favourites',
'title' => 'Most-loved picks that parents keep coming back to',
'copy' => 'A quick view of the products families reach for most often when they want easy, dependable feeding wins.',
],
[
'eyebrow' => 'Make feeding easier',
'title' => 'With NumNam',
'copy' => 'Doctor-parent thinking, practical nutrition, and formats that work both at home and on the go.',
],
[
'eyebrow' => 'How NumNam began',
'title' => 'European-inspired standards, built for Indian families',
'copy' => 'The idea started with doctor-parents who wanted cleaner baby food choices that still felt realistic and family-friendly.',
],
[
'eyebrow' => 'Guidance for every feeding stage',
'title' => 'Learn while you shop',
'copy' => 'Explore practical feeding support, ingredient transparency, and stage-wise guidance that helps parents feel more confident.',
],
];
@endphp

<div id="nn-fp-fixed">
    <div id="nn-fp-wrapper">
        <section class="nn-home-hero-v2 nn-fp-section">
            <img src="{{ asset('assets/images/bg_with_child.jpeg') }}" alt="" aria-hidden="true" class="nn-home-hero-v2__bg">
            <div class="nn-home-hero-v2__veil"></div>
            <div class="nn-home-hero-v2__glow nn-home-hero-v2__glow--left"></div>
            <div class="nn-home-hero-v2__glow nn-home-hero-v2__glow--right"></div>

            <div class="nn-home-shell nn-home-hero-v2__inner">
                <div class="nn-home-hero-v2__copy">
                    <p class="nn-home-kicker">Doctor-Founded &middot; Clean Label &middot; European Standards</p>
                    <h1>Vegetable Rich Baby Food</h1>
                    <p class="nn-home-hero-v2__subtitle">Inspired-By European Nutrition Standards</p>
                    <p class="nn-home-hero-v2__meta">
                        Made by <strong>Doctor Parents</strong>
                        <span>&middot;</span>
                        No added sugars
                        <span>&middot;</span>
                        No preservatives
                    </p>
                    <p class="nn-home-hero-v2__description">
                        <em>Real Fruits &amp; Vegetables</em> for your little one, in practical formats that make feeding feel simpler, cleaner, and more joyful.
                    </p>
                    <div class="nn-home-hero-v2__actions">
                        <a href="{{ route('store.products') }}" class="nn-home-btn nn-home-btn--primary">Shop Now</a>
                        <a href="{{ route('store.about') }}" class="nn-home-btn nn-home-btn--ghost">Our Story</a>
                    </div>
                </div>

            </div>
        </section>

        <section class="nn-home-trust nn-fp-section">
            <div class="nn-home-shell">
                <div class="nn-home-trust__head">
                    <p class="nn-home-kicker">Why parents choose us</p>
                    <h2>Why Parents Trust <span>Num Nam</span></h2>
                </div>

                <div class="nn-home-trust__grid">
                    @foreach($trustItems as $item)
                    <article class="nn-home-trust__item">
                        <div class="nn-home-trust__badge" aria-hidden="true">
                            @if($item['icon'] === 'doctor')
                            <svg viewBox="0 0 64 64" fill="none">
                                <rect x="16" y="10" width="32" height="44" rx="12" fill="#E7F1FB" stroke="#4E88B7" stroke-width="2.5" />
                                <path d="M25 23h14" stroke="#4E88B7" stroke-width="3.5" stroke-linecap="round" />
                                <path d="M32 16v14" stroke="#4E88B7" stroke-width="3.5" stroke-linecap="round" />
                                <path d="M21 39c2.6-4.2 6.6-6.3 11-6.3S40.4 34.8 43 39" stroke="#1F5F8B" stroke-width="2.8" stroke-linecap="round" />
                                <circle cx="32" cy="29" r="5.5" fill="#FFFFFF" stroke="#1F5F8B" stroke-width="2.5" />
                                <path d="M24 48h16" stroke="#7FB4D6" stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                            @elseif($item['icon'] === 'veg')
                            <svg viewBox="0 0 64 64" fill="none">
                                <path d="M32 15c10.8 0 18 6.2 18 16.4 0 10.9-8.6 18.6-18 18.6s-18-7.7-18-18.6C14 21.2 21.2 15 32 15Z" fill="#E8F6E4" stroke="#4E9B4E" stroke-width="2.5" />
                                <path d="M32 18v28" stroke="#2F7C3A" stroke-width="2.8" stroke-linecap="round" />
                                <path d="M32 32c0-6.8 5-12.2 12.5-13.8" stroke="#2F7C3A" stroke-width="2.6" stroke-linecap="round" />
                                <path d="M32 36c0-6.2-4.7-11.1-11.6-12.9" stroke="#2F7C3A" stroke-width="2.6" stroke-linecap="round" />
                                <path d="M21 46c2.8-1.6 6.5-2.6 11-2.6 4.6 0 8.3 1 11 2.6" stroke="#7CC46B" stroke-width="2.4" stroke-linecap="round" />
                            </svg>
                            @elseif($item['icon'] === 'sugar')
                            <svg viewBox="0 0 64 64" fill="none">
                                <rect x="19" y="14" width="26" height="36" rx="10" fill="#FFF4D9" stroke="#D3A640" stroke-width="2.5" />
                                <path d="M25 24h14" stroke="#D3A640" stroke-width="2.8" stroke-linecap="round" />
                                <path d="M25 31h9" stroke="#D3A640" stroke-width="2.8" stroke-linecap="round" />
                                <path d="M25 38h7" stroke="#D3A640" stroke-width="2.8" stroke-linecap="round" />
                                <path d="M47 18l-8 8" stroke="#E56A86" stroke-width="3.4" stroke-linecap="round" />
                                <path d="M39 18l8 8" stroke="#E56A86" stroke-width="3.4" stroke-linecap="round" />
                                <circle cx="32" cy="46" r="2.4" fill="#E56A86" />
                            </svg>
                            @else
                            <svg viewBox="0 0 64 64" fill="none">
                                <path d="M32 11 46 16.6v11.7c0 11-5.9 17.8-14 22.7-8.1-4.9-14-11.7-14-22.7V16.6L32 11Z" fill="#FFE8EE" stroke="#E56A86" stroke-width="2.6" stroke-linejoin="round" />
                                <path d="M32 22v15" stroke="#C84D6C" stroke-width="3" stroke-linecap="round" />
                                <path d="M24.5 29.5H39.5" stroke="#C84D6C" stroke-width="3" stroke-linecap="round" />
                                <path d="M24.5 42c2.6-1.8 5.1-2.7 7.5-2.7s4.9.9 7.5 2.7" stroke="#F39AB3" stroke-width="2.4" stroke-linecap="round" />
                            </svg>
                            @endif
                        </div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['caption'] }}</p>
                    </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="nn-home-section nn-home-carousel-section nn-home-soft-bg nn-fp-section">
            <div class="nn-home-shell">
                <p class="nn-home-kicker">Stage 1 &middot; 6+ Months</p>
                <h2 class="nn-home-section-title">Num Nam Purees</h2>

                <div class="relative nn-carousel-shell" style="padding:0 56px;">
                    <button class="nn-arrow-btn" onclick="nnCarousel('puree',-1)" aria-label="Previous puree" style="position:absolute;left:0;top:50%;transform:translateY(-50%);">&#8249;</button>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                        <div class="nn-home-editorial-card">
                            <h3>Why Parents love<br>Num Nam Purees</h3>
                            <ul>
                                @foreach(['Helps introduce vegetables early','Smooth and easy to serve','Great for home or travel','No preservatives','No added sugar','No added salt'] as $pt)
                                <li>{{ $pt }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="nn-home-product-stage">
                            <h3>Purees</h3>
                            @foreach($pureeItems as $i => $item)
                            <div class="nn-puree-slide" data-idx="{{ $i }}" @if($i !==0) style="display:none;" @endif>
                                <a href="{{ route('store.product.show', $item['slug']) }}">
                                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" style="height:220px;width:100%;object-fit:contain;display:block;">
                                </a>
                                <p>{{ $item['name'] }}</p>
                            </div>
                            @endforeach
                            <a href="{{ route('store.products') }}" class="nn-home-inline-link">Explore All Purees &rarr;</a>
                        </div>

                        <div class="nn-home-editorial-card nn-home-editorial-card--right">
                            <h3>What NumNam<br>Purees Are</h3>
                            <p>NumNam Purees are smooth, easy-to-serve fruit and vegetable pouches made for babies beginning their food journey and exploring new tastes from 6+ months onward.</p>
                            <ul>
                                @foreach(['Smooth texture for early feeding','Easy pouch format','Vegetable-forward combinations','Made for tiny taste explorers'] as $pt)
                                <li>{{ $pt }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="nn-arrow-btn" onclick="nnCarousel('puree',1)" aria-label="Next puree" style="position:absolute;right:0;top:50%;transform:translateY(-50%);">&#8250;</button>
                </div>
            </div>
        </section>

        <section class="nn-home-section nn-home-carousel-section nn-home-white-bg nn-fp-section">
            <div class="nn-home-shell">
                <p class="nn-home-kicker">Stage 2 &middot; 8+ Months</p>
                <h2 class="nn-home-section-title">Num Nam Puffs</h2>

                <div class="relative nn-carousel-shell" style="padding:0 56px;">
                    <button class="nn-arrow-btn" onclick="nnCarousel('puff',-1)" aria-label="Previous puff" style="position:absolute;left:0;top:50%;transform:translateY(-50%);">&#8249;</button>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                        <div class="nn-home-editorial-card">
                            <h3>Why parents choose<br>NumNam Puffs</h3>
                            <p>Our puffs are designed to be a more thoughtful snacking option.</p>
                            <ul>
                                @foreach(['Combining convenience','Playful texture','Carefully chosen ingredients','A format children enjoy'] as $pt)
                                <li>{{ $pt }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="nn-home-product-stage">
                            <h3>Puffs</h3>
                            @foreach($puffItems as $i => $item)
                            <div class="nn-puff-slide" data-idx="{{ $i }}" @if($i !==0) style="display:none;" @endif>
                                <a href="{{ route('store.product.show', $item['slug']) }}">
                                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" style="height:220px;width:100%;object-fit:contain;display:block;">
                                </a>
                                <p>{{ $item['name'] }}</p>
                            </div>
                            @endforeach
                            <a href="{{ route('store.products') }}" class="nn-home-inline-link">Explore All Puffs &rarr;</a>
                        </div>

                        <div class="nn-home-editorial-card nn-home-editorial-card--right">
                            <h3>What they Are</h3>
                            <p>NumNam Puffs are light, easy-to-hold finger snacks created for babies and young children as they begin self-feeding, texture exploration, and independent snacking.</p>
                            <ul>
                                @foreach(['Easy for little hands to hold','Gentle crunchy texture','Made for snack time and on-the-go','Designed for growing kids'] as $pt)
                                <li>{{ $pt }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button class="nn-arrow-btn" onclick="nnCarousel('puff',1)" aria-label="Next puff" style="position:absolute;right:0;top:50%;transform:translateY(-50%);">&#8250;</button>
                </div>
            </div>
        </section>

        <section class="nn-home-block5 nn-fp-section">
            <div class="nn-home-shell">
                <div class="nn-home-block5__head">
                    <h2>Why NumNam Matters</h2>
                </div>

                <div class="nn-home-block5__grid">
                    <article class="nn-home-block5__card nn-home-block5__card--cream">
                        <p class="nn-home-block5__eyebrow">{{ $blockCards[0]['eyebrow'] }}</p>
                        <h3>{{ $blockCards[0]['title'] }}</h3>
                        <p>{{ $blockCards[0]['copy'] }}</p>
                        <div class="nn-home-block5__products">
                            @foreach($favItems as $fav)
                            <div class="nn-home-mini-product">
                                <img src="{{ $fav['img'] }}" alt="{{ $fav['name'] }}" loading="lazy">
                                <span>{{ $fav['name'] }}</span>
                                <a href="{{ route('store.product.show', $fav['slug']) }}">Add to Cart</a>
                            </div>
                            @endforeach
                        </div>
                    </article>

                    <article class="nn-home-block5__card nn-home-block5__card--mint">
                        <p class="nn-home-block5__eyebrow">{{ $blockCards[1]['eyebrow'] }}</p>
                        <h3>{{ $blockCards[1]['title'] }}</h3>
                        <p>{{ $blockCards[1]['copy'] }}</p>
                        <a href="{{ route('store.about') }}" class="nn-home-btn nn-home-btn--small">Visit the Learn Section</a>
                    </article>

                    <article class="nn-home-block5__card nn-home-block5__card--blush">
                        <p class="nn-home-block5__eyebrow">{{ $blockCards[2]['eyebrow'] }}</p>
                        <h3>{{ $blockCards[2]['title'] }}</h3>
                        <p>{{ $blockCards[2]['copy'] }}</p>
                        <a href="{{ route('store.about') }}" class="nn-home-btn nn-home-btn--small nn-home-btn--ghost-soft">Read Our Story</a>
                    </article>

                    <article class="nn-home-block5__card nn-home-block5__card--sky">
                        <p class="nn-home-block5__eyebrow">{{ $blockCards[3]['eyebrow'] }}</p>
                        <h3>{{ $blockCards[3]['title'] }}</h3>
                        <p>{{ $blockCards[3]['copy'] }}</p>
                        <div class="nn-home-block5__actions">
                            <a href="{{ route('store.products') }}" class="nn-home-btn nn-home-btn--small">View Store</a>
                            <a href="{{ route('store.blog.index') }}" class="nn-home-btn nn-home-btn--small nn-home-btn--ghost-soft">Learn More</a>
                        </div>
                    </article>
                </div>
            </div>
        </section>

    </div><!-- /#nn-fp-wrapper -->
</div><!-- /#nn-fp-fixed -->
<div id="nn-fp-spacer" aria-hidden="true"></div>
<div id="nn-fp-normal">
    <section class="nn-home-section nn-home-range" style="position:relative;width:100%;">
        <img src="{{ asset('assets/images/bg_products.png') }}" alt="" aria-hidden="true" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
        <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,25,12,0.48) 0%,rgba(10,25,12,0.40) 100%);"></div>
        <div style="position:relative;z-index:10;width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">
            <div class="text-center mb-12">
                <p style="font-family:'Poppins',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:0.24em;text-transform:uppercase;color:rgba(180,240,180,0.80);margin-bottom:0.9rem;">Explore Our Range</p>
                <h2 style="font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:800;color:#ffffff;text-transform:uppercase;letter-spacing:-0.01em;line-height:1.05;">All Products</h2>
            </div>

            <div class="flex justify-center mb-10" style="gap:0.5rem;flex-wrap:wrap;">
                <button onclick="nnTab('purees')" id="tab-purees" style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);">Purees</button>
                <button onclick="nnTab('puffs')" id="tab-puffs" style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);">Puffs</button>
                <a href="{{ route('store.products') }}" style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;transition:all 0.2s;background:#ffffff;color:#1A1A2E;border:2px solid #ffffff;text-decoration:none;display:inline-block;">All</a>
            </div>

            <div id="tabpanel-purees" class="grid grid-cols-2 gap-5 md:grid-cols-4 nn-tab-panel-grid" style="display:none;">
                @foreach($pureeGalleryItems as $item)
                <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" style="height:180px;width:100%;object-fit:contain;display:block;">
                </a>
                @endforeach
            </div>

            <div id="tabpanel-puffs" class="grid grid-cols-2 gap-5 md:grid-cols-4 nn-tab-panel-grid" style="display:none;">
                @foreach($puffGalleryItems as $item)
                <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" style="height:180px;width:100%;object-fit:contain;display:block;">
                </a>
                @endforeach
            </div>

            <div id="tabpanel-all" class="grid grid-cols-2 gap-5 md:grid-cols-4 nn-tab-panel-grid">
                @foreach(array_merge($pureeGalleryItems, $puffGalleryItems) as $item)
                <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                    <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy" style="height:180px;width:100%;object-fit:contain;display:block;">
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @include('store.partials.footer')
</div><!-- /#nn-fp-normal -->
@endsection

@section('scripts')
<style>
    .nn-home-shell {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 clamp(1.5rem, 5vw, 3rem);
    }

    .nn-home-kicker {
        margin: 0 0 0.9rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #2D7C3A;
    }

    .nn-home-section-title {
        margin: 0 0 2.5rem;
        font-family: 'Poppins', sans-serif;
        font-size: clamp(1.9rem, 3.6vw, 3.2rem);
        font-weight: 800;
        line-height: 1.04;
        letter-spacing: -0.02em;
        color: #171B24;
        text-transform: uppercase;
    }

    .nn-home-hero-v2 {
        position: relative;
        overflow: hidden;
        min-height: 680px;
        background: linear-gradient(180deg, #FBFAF5 0%, #FFFDF8 100%);
    }

    .nn-home-hero-v2__bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center right;
    }

    .nn-home-hero-v2__veil {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255, 250, 242, 0.72) 0%, rgba(255, 250, 242, 0.58) 38%, rgba(255, 250, 242, 0.26) 58%, rgba(255, 250, 242, 0.04) 100%);
    }

    .nn-home-hero-v2__glow {
        position: absolute;
        border-radius: 999px;
        filter: blur(40px);
        opacity: 0.55;
    }

    .nn-home-hero-v2__glow--left {
        left: -80px;
        top: 90px;
        width: 280px;
        height: 280px;
        background: rgba(255, 227, 154, 0.62);
    }

    .nn-home-hero-v2__glow--right {
        right: 10%;
        bottom: 80px;
        width: 260px;
        height: 260px;
        background: rgba(185, 228, 177, 0.42);
    }

    .nn-home-hero-v2__inner {
        position: relative;
        z-index: 2;
        min-height: 680px;
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        align-items: flex-start;
        gap: 2rem;
        padding-top: 4.5rem;
        padding-bottom: 4rem;
    }

    .nn-home-hero-v2__copy {
        max-width: 760px;
        margin: 0 auto;
        text-align: center;
    }

    .nn-home-hero-v2__copy h1 {
        margin: 0;
        max-width: 760px;
        font-family: 'Poppins', sans-serif;
        font-size: clamp(2.7rem, 5.2vw, 5.25rem);
        font-weight: 800;
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: #111111;
        text-transform: uppercase;
    }

    .nn-home-hero-v2__subtitle {
        margin: 1rem 0 0;
        display: inline-block;
        padding-bottom: 0.55rem;
        border-bottom: 3px solid rgba(151, 140, 255, 0.45);
        font-family: 'Poppins', sans-serif;
        font-size: clamp(1.15rem, 2vw, 1.75rem);
        font-weight: 500;
        color: #4D9954;
    }

    .nn-home-hero-v2__meta {
        margin: 1.8rem 0 0;
        display: flex;
        flex-wrap: wrap;
        gap: 0.65rem;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #262626;
    }

    .nn-home-hero-v2__meta strong {
        color: #0E0E0E;
    }

    .nn-home-hero-v2__meta span {
        color: #8AAA71;
    }

    .nn-home-hero-v2__description {
        margin: 1.2rem 0 0;
        max-width: 540px;
        margin-left: auto;
        margin-right: auto;
        font-size: 1.08rem;
        line-height: 1.8;
        color: #2F2F2F;
    }

    .nn-home-hero-v2__description em {
        font-style: italic;
        font-weight: 700;
        color: #3F9D55;
    }

    .nn-home-hero-v2__actions {
        margin-top: 2.3rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.9rem;
        justify-content: center;
    }

    .nn-home-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        border-radius: 999px;
        transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
    }

    .nn-home-btn:hover {
        transform: translateY(-2px);
    }

    .nn-home-btn--primary {
        min-height: 58px;
        padding: 0 2rem;
        background: #3E9A50;
        color: #FFFFFF;
        box-shadow: 0 12px 24px rgba(62, 154, 80, 0.2);
    }

    .nn-home-btn--ghost {
        min-height: 58px;
        padding: 0 1.5rem;
        border: 1.5px solid rgba(28, 28, 28, 0.14);
        background: rgba(255, 255, 255, 0.82);
        color: #18202A;
    }

    .nn-home-btn--small {
        min-height: 46px;
        padding: 0 1.1rem;
        background: #2D5A27;
        color: #FFFFFF;
    }

    .nn-home-btn--ghost-soft {
        background: #FFFFFF;
        color: #1A1A2E;
        border: 1.5px solid rgba(26, 26, 46, 0.16);
    }

    .nn-home-hero-v2__visual {
        display: none;
    }

    .nn-home-hero-v2__featured-strip {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.9rem;
        width: min(100%, 540px);
        margin-top: auto;
        align-items: end;
        padding: 1.15rem;
        border-radius: 32px;
        background: rgba(255, 255, 255, 0.72);
        border: 1px solid rgba(255, 255, 255, 0.74);
        box-shadow: 0 18px 44px rgba(44, 51, 61, 0.08);
        backdrop-filter: blur(8px);
    }

    .nn-home-hero-v2__featured-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.55rem;
        padding: 1rem 0.7rem 0.85rem;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(232, 224, 206, 0.95);
        box-shadow: 0 8px 18px rgba(18, 24, 32, 0.05);
    }

    .nn-home-hero-v2__featured-card:nth-child(1),
    .nn-home-hero-v2__featured-card:nth-child(3) {
        transform: translateY(34px);
    }

    .nn-home-hero-v2__featured-card img {
        width: 100%;
        height: 180px;
        object-fit: contain;
        display: block;
    }

    .nn-home-hero-v2__featured-card span {
        font-family: 'Poppins', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        color: #20582B;
        text-align: center;
    }

    .nn-home-trust {
        position: relative;
        background: linear-gradient(180deg, #FBFAF5 0%, #F6F1E5 100%);
        padding: 72px 0 82px;
        overflow: hidden;
    }

    .nn-home-trust::before {
        content: '';
        position: absolute;
        inset: auto 0 0;
        height: 120px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(232, 246, 224, 0.58) 100%);
        pointer-events: none;
    }

    .nn-home-trust__head {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .nn-home-trust__head h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: clamp(2.2rem, 4vw, 4.4rem);
        line-height: 1.03;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #111111;
    }

    .nn-home-trust__head span {
        color: #56A35E;
    }

    .nn-home-trust__grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.5rem;
    }

    .nn-home-trust__item {
        text-align: center;
        padding: 1rem 1rem 0;
    }

    .nn-home-trust__badge {
        width: 88px;
        height: 88px;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, #FFFFFF 0%, #F4F8EB 48%, #EDF5DF 100%);
        border: 1px solid rgba(92, 135, 92, 0.15);
        box-shadow: 0 12px 22px rgba(126, 159, 103, 0.14);
    }

    .nn-home-trust__badge svg {
        width: 58px;
        height: 58px;
    }

    .nn-home-trust__item h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 800;
        color: #111111;
    }

    .nn-home-trust__item p {
        margin: 0.65rem auto 0;
        max-width: 240px;
        font-size: 0.95rem;
        line-height: 1.65;
        color: #5B5F67;
    }

    .nn-home-soft-bg,
    .nn-home-white-bg,
    .nn-home-block5 {
        background-image: url("{{ asset('assets/images/bg_content.png') }}");
        background-size: cover;
        background-position: center;
    }

    .nn-home-soft-bg,
    .nn-home-white-bg {
        padding: 88px 0;
    }

    .nn-home-editorial-card,
    .nn-home-product-stage {
        background: rgba(255, 255, 255, 0.94);
        border-radius: 30px;
        padding: 2rem 1.7rem;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(238, 227, 203, 0.9);
    }

    .nn-home-editorial-card h3,
    .nn-home-product-stage h3 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: 1.32rem;
        line-height: 1.2;
        font-weight: 800;
        color: #1A1A2E;
    }

    .nn-home-editorial-card p {
        margin: 1rem 0 0;
        font-size: 0.96rem;
        line-height: 1.7;
        color: #595D63;
    }

    .nn-home-editorial-card ul {
        list-style: none;
        margin: 1.15rem 0 0;
        padding: 0;
        display: grid;
        gap: 0.8rem;
    }

    .nn-home-editorial-card li {
        position: relative;
        padding-left: 1.35rem;
        font-size: 0.95rem;
        line-height: 1.55;
        color: #2F3542;
    }

    .nn-home-editorial-card li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.45rem;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: #F07AA2;
        box-shadow: 0 0 0 4px rgba(240, 122, 162, 0.14);
    }

    .nn-home-product-stage {
        text-align: center;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 249, 239, 0.95) 100%);
    }

    .nn-home-product-stage p {
        margin: 0.9rem 0 0;
        font-family: 'Poppins', sans-serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: #2D5A27;
    }

    .nn-home-block5 {
        padding: 92px 0;
    }

    .nn-home-block5__head {
        text-align: center;
        margin-bottom: 2.8rem;
    }

    .nn-home-block5__head h2 {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        font-size: clamp(2rem, 3.7vw, 3.4rem);
        line-height: 1.06;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #13151D;
    }

    .nn-home-block5__grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.35rem;
    }

    .nn-home-block5__card {
        position: relative;
        min-height: 290px;
        display: flex;
        flex-direction: column;
        border-radius: 30px;
        padding: 1.8rem;
        box-shadow: 0 14px 32px rgba(18, 24, 32, 0.06);
        border: 1px solid rgba(214, 214, 214, 0.8);
        background: rgba(255, 255, 255, 0.98);
    }

    .nn-home-block5__card--cream {
        background: linear-gradient(180deg, rgba(255, 250, 238, 0.98) 0%, rgba(255, 255, 255, 0.98) 100%);
    }

    .nn-home-block5__card--mint {
        background: linear-gradient(180deg, rgba(238, 247, 236, 0.98) 0%, rgba(255, 255, 255, 0.98) 100%);
    }

    .nn-home-block5__card--blush {
        background: linear-gradient(180deg, rgba(252, 242, 244, 0.98) 0%, rgba(255, 255, 255, 0.98) 100%);
    }

    .nn-home-block5__card--sky {
        background: linear-gradient(180deg, rgba(239, 247, 252, 0.98) 0%, rgba(255, 255, 255, 0.98) 100%);
    }

    .nn-home-block5__eyebrow {
        margin: 0 0 0.8rem;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #4C8F55;
    }

    .nn-home-block5__card h3 {
        margin: 0;
        max-width: 430px;
        font-family: 'Poppins', sans-serif;
        font-size: 1.75rem;
        line-height: 1.14;
        font-weight: 800;
        color: #151922;
    }

    .nn-home-block5__card p {
        margin: 0.95rem 0 0;
        max-width: 520px;
        font-size: 0.98rem;
        line-height: 1.7;
        color: #5B5F67;
    }

    .nn-home-block5__card>.nn-home-btn {
        margin-top: auto;
        align-self: flex-start;
    }

    .nn-home-block5__products {
        margin-top: 1.5rem;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.8rem;
    }

    .nn-home-mini-product {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 0.95rem 0.75rem;
        border-radius: 22px;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(232, 222, 202, 0.95);
        text-align: center;
        min-height: 100%;
    }

    .nn-home-mini-product img {
        width: 100%;
        height: 78px;
        object-fit: contain;
        display: block;
        margin-bottom: 0.55rem;
    }

    .nn-home-mini-product span {
        font-size: 0.76rem;
        line-height: 1.3;
        font-weight: 700;
        color: #1D2430;
    }

    .nn-home-mini-product a {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 0 0.85rem;
        border-radius: 999px;
        background: #2D5A27;
        color: #FFFFFF;
        text-decoration: none;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .nn-home-block5__actions {
        margin-top: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .nn-arrow-btn {
        background: #F07AA2;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.6rem;
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(240, 122, 162, 0.4);
        z-index: 5;
        transition: background 0.2s;
        line-height: 1;
    }

    .nn-arrow-btn:hover {
        background: #D95E8C;
    }

    @media (max-width: 1199px) {
        .nn-home-shell {
            padding: 0 clamp(1rem, 3vw, 2rem);
        }

        .nn-home-kicker {
            margin-bottom: 0.65rem;
            font-size: 0.68rem;
            letter-spacing: 0.18em;
        }

        .nn-home-section-title {
            margin-bottom: 1.6rem;
            font-size: clamp(1.55rem, 2.8vw, 2.45rem);
        }

        .nn-home-hero-v2__inner {
            gap: 1.35rem;
            padding-top: 2.5rem;
            padding-bottom: 2rem;
        }

        .nn-home-hero-v2__copy h1 {
            font-size: clamp(2.2rem, 4.5vw, 4rem);
        }

        .nn-home-hero-v2__subtitle {
            font-size: clamp(0.98rem, 1.6vw, 1.3rem);
        }

        .nn-home-hero-v2__meta,
        .nn-home-hero-v2__description {
            font-size: 0.94rem;
            line-height: 1.6;
        }

        .nn-home-btn--primary,
        .nn-home-btn--ghost {
            min-height: 52px;
            padding-inline: 1.5rem;
        }

        .nn-home-trust__head {
            margin-bottom: 1.6rem;
        }

        .nn-home-trust__head h2 {
            font-size: clamp(1.8rem, 3.2vw, 2.8rem);
        }

        .nn-home-trust__grid {
            gap: 1rem;
        }

        .nn-home-trust__item {
            padding: 0.7rem 0.5rem 0;
        }

        .nn-home-trust__badge {
            width: 72px;
            height: 72px;
            margin-bottom: 0.8rem;
        }

        .nn-home-trust__badge svg {
            width: 48px;
            height: 48px;
        }

        .nn-home-trust__item h3 {
            font-size: 1.08rem;
        }

        .nn-home-trust__item p {
            margin-top: 0.5rem;
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .nn-home-editorial-card,
        .nn-home-product-stage {
            border-radius: 24px;
            padding: 1.25rem 1rem;
        }

        .nn-home-editorial-card h3,
        .nn-home-product-stage h3 {
            font-size: 1.08rem;
        }

        .nn-home-editorial-card p,
        .nn-home-editorial-card li,
        .nn-home-product-stage p {
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .nn-home-editorial-card ul {
            margin-top: 0.9rem;
            gap: 0.55rem;
        }

        .nn-home-product-stage img {
            height: 150px !important;
        }

        .nn-home-block5__head {
            margin-bottom: 1.5rem;
        }

        .nn-home-block5__head h2 {
            font-size: clamp(1.7rem, 2.8vw, 2.45rem);
        }

        .nn-home-block5__grid {
            gap: 1rem;
        }

        .nn-home-block5__card {
            min-height: 220px;
            border-radius: 24px;
            padding: 1.15rem;
        }

        .nn-home-block5__eyebrow {
            margin-bottom: 0.55rem;
            font-size: 0.72rem;
        }

        .nn-home-block5__card h3 {
            font-size: 1.2rem;
            line-height: 1.12;
        }

        .nn-home-block5__card p {
            margin-top: 0.7rem;
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .nn-home-block5__products {
            margin-top: 1rem;
            gap: 0.55rem;
        }

        .nn-home-mini-product {
            padding: 0.7rem 0.45rem;
            border-radius: 18px;
        }

        .nn-home-mini-product img {
            height: 56px;
            margin-bottom: 0.4rem;
        }

        .nn-home-mini-product span {
            font-size: 0.66rem;
        }

        .nn-home-mini-product a {
            min-height: 30px;
            padding: 0 0.7rem;
            font-size: 0.64rem;
        }
    }

    @media (max-width: 1023px) {
        .nn-home-hero-v2__inner {
            grid-template-columns: 1fr;
            gap: 1.25rem;
            padding-top: 2rem;
            padding-bottom: 1.75rem;
        }

        .nn-home-hero-v2__visual {
            min-height: auto;
        }

        .nn-home-trust__grid,
        .nn-home-block5__grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .nn-home-hero-v2 {
            min-height: auto;
        }

        .nn-home-kicker {
            font-size: 0.62rem;
            letter-spacing: 0.14em;
            margin-bottom: 0.45rem;
        }

        .nn-home-hero-v2__veil {
            background: linear-gradient(180deg, rgba(255, 250, 242, 0.64) 0%, rgba(255, 250, 242, 0.56) 48%, rgba(255, 250, 242, 0.44) 100%);
        }

        .nn-home-hero-v2__inner {
            padding-top: 1.5rem;
            padding-bottom: 1.25rem;
            min-height: auto;
        }

        .nn-home-hero-v2__copy h1 {
            font-size: clamp(1.65rem, 8vw, 2.45rem);
        }

        .nn-home-hero-v2__subtitle {
            font-size: 0.84rem;
        }

        .nn-home-hero-v2__meta {
            font-size: 0.76rem;
            gap: 0.4rem;
        }

        .nn-home-hero-v2__description {
            font-size: 0.78rem;
            line-height: 1.4;
        }

        .nn-home-hero-v2__actions {
            flex-direction: column;
            margin-top: 1rem;
        }

        .nn-home-btn--primary,
        .nn-home-btn--ghost {
            width: 100%;
        }

        .nn-home-hero-v2__featured-strip {
            grid-template-columns: repeat(3, minmax(88px, 1fr));
            gap: 0.65rem;
            padding: 0.85rem;
        }

        .nn-home-hero-v2__featured-card {
            padding: 0.8rem 0.55rem;
            border-radius: 22px;
        }

        .nn-home-hero-v2__featured-card img {
            height: 132px;
        }

        .nn-home-hero-v2__featured-card span {
            font-size: 0.72rem;
        }

        .nn-home-trust,
        .nn-home-soft-bg,
        .nn-home-white-bg,
        .nn-home-block5,
        .nn-home-range {
            padding-top: 34px !important;
            padding-bottom: 34px !important;
        }

        .nn-home-trust__grid,
        .nn-home-block5__grid {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }

        .nn-home-trust__head {
            margin-bottom: 1.1rem;
        }

        .nn-home-trust__head h2 {
            font-size: clamp(1.55rem, 7vw, 2.1rem);
        }

        .nn-home-trust__badge {
            width: 60px;
            height: 60px;
        }

        .nn-home-trust__badge svg {
            width: 40px;
            height: 40px;
        }

        .nn-home-trust__item h3 {
            font-size: 0.98rem;
        }

        .nn-home-trust__item p {
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .nn-home-carousel-section .nn-carousel-shell {
            padding: 0 0 70px !important;
        }

        .nn-home-editorial-card,
        .nn-home-product-stage {
            border-radius: 20px;
            padding: 1rem 0.9rem;
        }

        .nn-home-editorial-card h3,
        .nn-home-product-stage h3 {
            font-size: 0.98rem;
        }

        .nn-home-editorial-card p,
        .nn-home-editorial-card li,
        .nn-home-product-stage p {
            font-size: 0.81rem;
            line-height: 1.45;
        }

        .nn-carousel-shell .nn-arrow-btn {
            top: auto !important;
            bottom: 0;
            transform: none !important;
        }

        .nn-carousel-shell .nn-arrow-btn:first-of-type {
            left: calc(50% - 54px) !important;
        }

        .nn-carousel-shell .nn-arrow-btn:last-of-type {
            right: calc(50% - 54px) !important;
        }

        .nn-home-block5__products {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.5rem;
        }

        .nn-home-block5__head {
            margin-bottom: 1rem;
        }

        .nn-home-block5__head h2 {
            font-size: clamp(1.45rem, 6.8vw, 1.95rem);
        }

        .nn-home-block5__card {
            min-height: 0;
            border-radius: 20px;
            padding: 0.95rem;
        }

        .nn-home-block5__eyebrow {
            margin-bottom: 0.45rem;
            font-size: 0.66rem;
        }

        .nn-home-block5__card h3 {
            font-size: 1rem;
        }

        .nn-home-block5__card p {
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .nn-home-mini-product {
            padding: 0.55rem 0.4rem;
        }

        .nn-home-mini-product img {
            height: 48px;
        }

        .nn-home-mini-product span {
            font-size: 0.62rem;
        }

        .nn-home-mini-product a {
            min-height: 28px;
            font-size: 0.6rem;
        }

        .nn-home-block5__products .nn-home-mini-product:last-child {
            grid-column: 1 / -1;
            max-width: 180px;
            margin: 0 auto;
        }

        .nn-home-range .flex.justify-center {
            justify-content: stretch;
        }

        .nn-home-range .flex.justify-center>* {
            flex: 1 1 calc(50% - 0.5rem);
            text-align: center;
        }

        .nn-tab-panel-grid {
            gap: 1rem !important;
        }

        .nn-tab-panel-grid a img {
            height: 118px !important;
        }

        .nn-fp-section.nn-home-hero-v2 {
            padding-top: calc(var(--nn-header-h, 100px) - 8px);
            padding-bottom: 0.4rem;
        }

        .nn-fp-section.nn-home-hero-v2 .nn-home-hero-v2__inner {
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }
    }

    @media (max-height: 820px) {

        .nn-fp-section.nn-home-trust,
        .nn-fp-section.nn-home-block5,
        .nn-fp-section.nn-home-carousel-section {
            padding-bottom: 0.9rem;
        }

        .nn-home-hero-v2__inner {
            padding-top: 1.75rem;
            padding-bottom: 1rem;
        }

        .nn-home-trust__head,
        .nn-home-block5__head {
            margin-bottom: 1rem;
        }

        .nn-home-trust__badge {
            width: 64px;
            height: 64px;
            margin-bottom: 0.65rem;
        }

        .nn-home-editorial-card,
        .nn-home-product-stage,
        .nn-home-block5__card {
            padding: 1rem 0.9rem;
        }

        .nn-home-block5__card {
            min-height: 200px;
        }

        .nn-home-product-stage img {
            height: 132px !important;
        }
    }

    /* ===== Transform-based full-page scroll (homepage) ===== */

    html,
    body {
        scrollbar-width: thin;
        scrollbar-color: rgba(42, 42, 42, 0.35) transparent;
    }

    body::-webkit-scrollbar {
        width: 2px;
        height: 2px;
    }

    body::-webkit-scrollbar-track {
        background: transparent;
    }

    body::-webkit-scrollbar-thumb {
        background: rgba(42, 42, 42, 0.35);
        border-radius: 10px;
    }

    html.nn-slides-active,
    html.nn-slides-active body {
        overflow: hidden;
        scrollbar-width: none;
    }

    html.nn-slides-active body::-webkit-scrollbar {
        width: 0;
        height: 0;
    }

    /* Remove default page padding — handled per-section */
    body.store-home .page {
        width: 100%;
        max-width: 100%;
        padding: 0 !important;
    }

    /* Fixed viewport container: holds slides 1-5, always at top of screen */
    #nn-fp-fixed {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        height: 100dvh;
        overflow: hidden;
        z-index: 1;
    }

    /* Inner sliding wrapper — translates to show each slide */
    #nn-fp-wrapper {
        will-change: transform;
        -webkit-transition: transform 0.75s cubic-bezier(0.77, 0, 0.175, 1);
        transition: transform 0.75s cubic-bezier(0.77, 0, 0.175, 1);
    }

    #nn-fp-wrapper.nn-fp-no-transition {
        -webkit-transition: none !important;
        transition: none !important;
    }

    /* Spacer: one viewport height of empty space in document flow,
       pushes #nn-fp-normal below the fixed slide panel */
    #nn-fp-spacer {
        height: 100vh;
        height: 100dvh;
        pointer-events: none;
    }

    /* Normal content: z-index covers the fixed panel when scrolled into view */
    #nn-fp-normal {
        position: relative;
        z-index: 2;
    }

    /* Slides 1-5: each fills the full viewport */
    .nn-fp-section {
        box-sizing: border-box;
        height: 100vh;
        height: 100dvh;
        min-height: 100vh;
        min-height: 100dvh;
        max-height: 100vh;
        max-height: 100dvh;
        width: 100%;
        overflow-y: hidden;
        overflow-x: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .nn-fp-section.nn-home-hero-v2 {
        justify-content: center;
        align-items: stretch;
        padding-top: var(--nn-header-h, 100px);
        padding-bottom: clamp(1rem, 2.5vh, 1.75rem);
    }

    .nn-fp-section.nn-home-hero-v2 .nn-home-hero-v2__inner {
        min-height: auto;
        padding-top: clamp(0.5rem, 2vh, 1.1rem);
        padding-bottom: clamp(0.5rem, 2vh, 1.1rem);
    }

    /* Non-hero slides: push content below the fixed header */
    .nn-fp-section.nn-home-trust,
    .nn-fp-section.nn-home-block5,
    .nn-fp-section.nn-home-carousel-section {
        justify-content: center;
        align-items: stretch;
        padding-top: var(--nn-header-h, 100px);
        padding-bottom: clamp(1.5rem, 4vh, 3rem);
    }

    /* Section 6: normal page flow, fills at least one viewport, clears header */
    #nn-fp-normal .nn-home-range {
        min-height: 100vh;
        min-height: 100dvh;
        padding-top: var(--nn-header-h, 100px);
        padding-bottom: clamp(2rem, 5vh, 4rem);
    }

    @media (max-width: 767px) {
        #nn-fp-fixed {
            height: 100svh;
        }

        #nn-fp-spacer {
            height: 100svh;
        }

        .nn-fp-section {
            height: 100svh;
            min-height: 100svh;
            max-height: 100svh;
        }

        /* Keep mobile sticky header and bottom app bar from covering slide content */
        .nn-fp-section.nn-home-trust,
        .nn-fp-section.nn-home-carousel-section {
            padding-top: calc(var(--nn-header-h, 78px) + 14px);
            padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px));
        }

        .nn-fp-section.nn-home-trust .nn-home-trust__head,
        .nn-fp-section.nn-home-carousel-section .nn-home-section-title {
            margin-top: 0;
            margin-bottom: 0.9rem;
        }

        .nn-fp-section.nn-home-carousel-section .nn-home-kicker {
            margin-bottom: 0.4rem;
        }
    }
</style>
<script>
    (function() {
        function nnCarousel(type, dir) {
            var slides = document.querySelectorAll('.nn-' + type + '-slide');
            var current = 0;
            for (var i = 0; i < slides.length; i++) {
                if (slides[i].style.display !== 'none') {
                    current = i;
                    break;
                }
            }
            slides[current].style.display = 'none';
            var next = (current + dir + slides.length) % slides.length;
            slides[next].style.display = 'block';
        }
        window.nnCarousel = nnCarousel;
    }());

    // Transform-based full-page scroll controller
    (function() {
        var SECTIONS = document.querySelectorAll('.nn-fp-section');
        var current = 0;
        var isAnimating = false;
        var wrapper = document.getElementById('nn-fp-wrapper');
        var sectionOffsets = [];

        if (!wrapper || !SECTIONS.length) return;

        function sectionHeight() {
            var header = document.querySelector('.site-header');
            var hh = header ? header.offsetHeight : 100;
            document.documentElement.style.setProperty('--nn-header-h', hh + 'px');
            return window.innerHeight - hh;
        }

        function recalcLayout() {
            sectionHeight();
            sectionOffsets = Array.prototype.map.call(SECTIONS, function(section) {
                return section.offsetTop;
            });
        }

        function applyTransform() {
            var targetY = sectionOffsets[current] || 0;
            wrapper.style.transform = 'translate3d(0, ' + (-targetY) + 'px, 0)';
        }

        function goTo(index) {
            if (index < 0 || index >= SECTIONS.length) return;
            if (isAnimating || index === current) return;

            isAnimating = true;
            current = index;
            applyTransform();

            setTimeout(function() {
                isAnimating = false;
            }, 700);
        }

        window.addEventListener('wheel', function(e) {
            // Only intercept scroll when in the slide zone (page hasn't scrolled yet)
            if (window.scrollY > 20) return;
            var isLastSection = current === SECTIONS.length - 1;
            var isFirstSection = current === 0;

            if (e.deltaY > 0) {
                if (isLastSection) {
                    // Exit slide zone — scroll into normal page content
                    e.preventDefault();
                    document.documentElement.classList.remove('nn-slides-active');
                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });
                    return;
                }
                e.preventDefault();
                goTo(current + 1);
            } else {
                if (isFirstSection) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                goTo(current - 1);
            }
        }, {
            passive: false
        });

        var startY = 0;

        window.addEventListener('touchstart', function(e) {
            startY = e.touches[0].clientY;
        }, {
            passive: true
        });

        window.addEventListener('touchend', function(e) {
            if (window.scrollY > 20) return;

            var endY = e.changedTouches[0].clientY;
            var diff = startY - endY;
            var isLastSection = current === SECTIONS.length - 1;
            var isFirstSection = current === 0;

            if (Math.abs(diff) < 50) return;

            if (diff > 0) {
                if (isLastSection) {
                    document.documentElement.classList.remove('nn-slides-active');
                    window.scrollTo({
                        top: window.innerHeight,
                        behavior: 'smooth'
                    });
                } else {
                    goTo(current + 1);
                }
            } else {
                if (!isFirstSection) goTo(current - 1);
            }
        }, {
            passive: true
        });

        recalcLayout();
        applyTransform();

        function updateSlidesMode() {
            if (window.scrollY <= 20) {
                document.documentElement.classList.add('nn-slides-active');
            } else {
                document.documentElement.classList.remove('nn-slides-active');
            }
        }

        updateSlidesMode();

        window.addEventListener('scroll', function() {
            updateSlidesMode();
        }, {
            passive: true
        });

        window.addEventListener('resize', function() {
            recalcLayout();
            applyTransform();
            updateSlidesMode();
        });

    })();

    (function() {
        var tabs = ['purees', 'puffs'];
        var activeStyle = 'background:#ffffff;color:#1A1A2E;border:2px solid #ffffff;';
        var inactiveStyle = 'background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);';
        window.nnTab = function(active) {
            var allPanel = document.getElementById('tabpanel-all');
            if (allPanel) {
                allPanel.style.display = 'none';
            }
            tabs.forEach(function(t) {
                var btn = document.getElementById('tab-' + t);
                var panel = document.getElementById('tabpanel-' + t);
                if (!btn || !panel) {
                    return;
                }
                if (t === active) {
                    btn.style.cssText = 'font-family:\'Poppins\',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;' + activeStyle;
                    panel.style.display = 'grid';
                } else {
                    btn.style.cssText = 'font-family:\'Poppins\',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;' + inactiveStyle;
                    panel.style.display = 'none';
                }
            });
        };
    }());
</script>
@endsection