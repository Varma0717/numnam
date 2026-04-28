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
                                <rect x="13" y="12" width="38" height="40" rx="13" fill="#EAF4FF" stroke="#4E88B7" stroke-width="2.5" />
                                <path d="M24 25h16" stroke="#4E88B7" stroke-width="3.4" stroke-linecap="round" />
                                <path d="M32 17v16" stroke="#4E88B7" stroke-width="3.4" stroke-linecap="round" />
                                <path d="M22 52c1.8-6 6.3-10.2 10-10.2 3.8 0 8.2 4.2 10 10.2" stroke="#1F5F8B" stroke-width="2.6" stroke-linecap="round" />
                                <circle cx="25.5" cy="50.5" r="3.5" fill="#FFFFFF" stroke="#1F5F8B" stroke-width="2.2" />
                                <path d="M25.5 54v3.2" stroke="#1F5F8B" stroke-width="2.2" stroke-linecap="round" />
                                <path d="M38.5 44.5c0-3.3 2.7-6 6-6 2.8 0 5.2 1.9 5.8 4.5.4 1.8-.1 3.8-1.5 5.3-1.3 1.5-3.2 2.2-5.2 2.2-1.8 0-3.4-.6-4.6-1.8" stroke="#1F5F8B" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M44.5 50.5v3.5" stroke="#1F5F8B" stroke-width="2.2" stroke-linecap="round" />
                            </svg>
                            @elseif($item['icon'] === 'veg')
                            <svg viewBox="0 0 64 64" fill="none">
                                <ellipse cx="23" cy="37" rx="9" ry="12" fill="#FFE8B5" stroke="#E59A2E" stroke-width="2.3" />
                                <path d="M23 25v24" stroke="#D7801F" stroke-width="2.2" stroke-linecap="round" />
                                <path d="M17.5 31.5c2.5 1.5 5.7 2.4 9.8 2.4" stroke="#D7801F" stroke-width="2" stroke-linecap="round" />
                                <path d="M16.8 40.5c2.8 1.6 6.2 2.5 10.2 2.5" stroke="#D7801F" stroke-width="2" stroke-linecap="round" />
                                <path d="M36 45c9.8 0 14-7.2 14-14.7 0-8.5-6.2-14.3-14-14.3-7.8 0-14 5.8-14 14.3C22 37.8 26.2 45 36 45Z" fill="#E8F6E4" stroke="#4E9B4E" stroke-width="2.5" />
                                <path d="M36 19v22" stroke="#2F7C3A" stroke-width="2.8" stroke-linecap="round" />
                                <path d="M36 29.5c0-4.8 3.6-8.6 9.1-9.8" stroke="#2F7C3A" stroke-width="2.4" stroke-linecap="round" />
                                <path d="M36 32.2c0-4.4-3.3-7.9-8.4-9.1" stroke="#2F7C3A" stroke-width="2.4" stroke-linecap="round" />
                            </svg>
                            @elseif($item['icon'] === 'sugar')
                            <svg viewBox="0 0 64 64" fill="none">
                                <rect x="14" y="13" width="18" height="18" rx="4" fill="#FFF4D9" stroke="#D3A640" stroke-width="2.3" />
                                <rect x="32" y="13" width="18" height="18" rx="4" fill="#FFF4D9" stroke="#D3A640" stroke-width="2.3" />
                                <rect x="23" y="31" width="18" height="18" rx="4" fill="#FFF4D9" stroke="#D3A640" stroke-width="2.3" />
                                <path d="M18 17.5l10 10" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                                <path d="M28 17.5 18 27.5" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                                <path d="M36 17.5l10 10" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                                <path d="M46 17.5 36 27.5" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                                <path d="M27 35.5l10 10" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                                <path d="M37 35.5 27 45.5" stroke="#E56A86" stroke-width="3.2" stroke-linecap="round" />
                            </svg>
                            @else
                            <svg viewBox="0 0 64 64" fill="none">
                                <path d="M32 10 47 16v12.4c0 11.2-6.1 18.4-15 24-8.9-5.6-15-12.8-15-24V16L32 10Z" fill="#FFE8EE" stroke="#E56A86" stroke-width="2.6" stroke-linejoin="round" />
                                <path d="M32 21c6.3-6.6 15.7-.8 10.8 7.1-2.6 4.1-6.5 6.9-10.8 10-4.3-3.1-8.2-5.9-10.8-10-4.9-7.9 4.5-13.7 10.8-7.1Z" fill="#FFFFFF" stroke="#C84D6C" stroke-width="2.2" stroke-linejoin="round" />
                                <path d="M25.5 43.5c2.5 1.7 4.8 2.5 6.5 2.5 1.7 0 4-.8 6.5-2.5" stroke="#F39AB3" stroke-width="2.4" stroke-linecap="round" />
                                <path d="M21.5 18.5c1.7-1.1 3.1-1.6 4.4-1.6 1.8 0 3.4.9 4.9 2.6" stroke="#F39AB3" stroke-width="2" stroke-linecap="round" />
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

                    <article class="nn-home-block5__card nn-home-block5__card--blush nn-fp-mobile-hide">
                        <p class="nn-home-block5__eyebrow">{{ $blockCards[2]['eyebrow'] }}</p>
                        <h3>{{ $blockCards[2]['title'] }}</h3>
                        <p>{{ $blockCards[2]['copy'] }}</p>
                        <a href="{{ route('store.about') }}" class="nn-home-btn nn-home-btn--small nn-home-btn--ghost-soft">Read Our Story</a>
                    </article>

                    <article class="nn-home-block5__card nn-home-block5__card--sky nn-fp-mobile-hide">
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

        {{-- Mobile only: second slide for Why NumNam Matters (cards 3 & 4) --}}
        <section class="nn-home-block5b nn-fp-section">
            <div class="nn-home-shell">
                <div class="nn-home-block5__grid">
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

@section('head')
<link rel="stylesheet" href="{{ asset('assets/store/css/pages/home.css') }}?v={{ filemtime(public_path('assets/store/css/pages/home.css')) }}">
@endsection

@section('scripts')
<script src="{{ asset('assets/store/js/pages/home.js') }}?v={{ filemtime(public_path('assets/store/js/pages/home.js')) }}" defer></script>
@endsection