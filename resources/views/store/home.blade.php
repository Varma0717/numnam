@extends('store.layouts.app')

@section('title', 'NumNam — Vegetable Rich Baby Food | Doctor-Founded')
@section('meta_description', 'Doctor-founded baby food inspired by European nutrition standards. Vegetable-rich, no added sugars, no preservatives. Real fruits & vegetables for your little one.')

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
$favItems = array_merge(array_slice($pureeItems, 0, 1), array_slice($puffItems, 0, 2));
@endphp

{{-- SECTION 1 — HERO --}}
<section style="position:relative;width:100%;min-height:420px;overflow:hidden;display:flex;align-items:center;">
    <div style="position:absolute;inset:0;background-image:url('{{ asset('assets/images/bg_with_child.jpeg') }}');background-size:cover;background-position:center top;"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(110deg, rgba(10,30,12,0.86) 0%, rgba(10,30,12,0.60) 50%, rgba(10,30,12,0.18) 100%);"></div>
    <div style="position:relative;z-index:10;width:100%;max-width:1400px;margin:0 auto;padding:clamp(40px,7vw,70px) clamp(1.5rem,5vw,3rem);">
        <p style="font-family:'Poppins',sans-serif;font-size:0.78rem;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:rgba(180,240,180,0.90);margin-bottom:1rem;">Doctor-Founded &middot; Clean Label &middot; European Standards</p>
        <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,4.8rem);color:#FFFFFF;line-height:1.08;letter-spacing:-0.01em;font-weight:800;max-width:480px;text-transform:uppercase;">
            Vegetable Rich<br>Baby Food
        </h1>
        <p style="margin-top:1.25rem;font-size:clamp(1rem,1.8vw,1.18rem);color:rgba(200,255,200,0.90);font-style:italic;font-weight:500;">
            Inspired by European Nutrition Standards
        </p>
        <p style="margin-top:1.1rem;font-size:0.98rem;color:rgba(255,255,255,0.82);max-width:440px;line-height:1.8;">
            Made by <strong style="color:#ffffff;">Doctor Parents.</strong> No added sugars. No preservatives.
            <br><em style="color:rgba(200,255,200,0.85);">Real Fruits &amp; Vegetables</em> for your little one.
        </p>
        <div style="margin-top:2.5rem;display:flex;flex-wrap:wrap;gap:1rem;align-items:center;">
            <a href="{{ route('store.products') }}" class="nn-hero-btn"
                style="display:inline-flex;align-items:center;gap:8px;background:#2D5A27;color:#fff;font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9rem;padding:14px 36px;border-radius:4px;text-decoration:none;letter-spacing:0.08em;text-transform:uppercase;box-shadow:0 6px 28px rgba(0,0,0,0.30);">
                Shop Now
            </a>
            <a href="{{ route('store.about') }}"
                style="display:inline-flex;align-items:center;gap:8px;color:#fff;font-family:'Poppins',sans-serif;font-weight:600;font-size:0.85rem;letter-spacing:0.06em;text-transform:uppercase;text-decoration:none;border-bottom:1.5px solid rgba(255,255,255,0.50);padding-bottom:2px;">
                Our Story &rarr;
            </a>
        </div>
    </div>
</section>

{{-- SECTION 2 — FIRST FOODS MATTER --}}
<section style="background:#F8F5F0;width:100%;padding:80px 0;">
    <div style="width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">
        <p style="font-family:'Poppins',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#2D7C3A;margin-bottom:1rem;">Why parents trust us</p>
        <h2 style="font-family:'Poppins',sans-serif;font-size:clamp(2.2rem,5vw,4rem);font-weight:800;color:#1A1A2E;text-transform:uppercase;letter-spacing:-0.01em;line-height:1.05;max-width:560px;margin-bottom:3.5rem;">
            First Foods<br>Matter
        </h2>
        <div class="grid grid-cols-2 gap-y-10 gap-x-6 sm:grid-cols-4 sm:gap-10">
            <div class="flex flex-col items-center text-center gap-3">
                <div style="width:72px;height:72px;border-radius:50%;background:rgba(45,90,39,0.08);border:1.5px solid rgba(45,90,39,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <svg width="34" height="34" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                        <path d="M11 8v5a5 5 0 0 0 10 0V8" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" fill="none" />
                        <path d="M11 8h10" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" />
                        <path d="M16 18v5" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" />
                        <circle cx="24" cy="23" r="4" fill="#2D5A27" />
                        <path d="M22.5 23h3M24 21.5v3" stroke="#F8F5F0" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </div>
                <p style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.85rem;color:#1A1A2E;letter-spacing:0.06em;text-transform:uppercase;">Doctor-Founded</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <div style="width:72px;height:72px;border-radius:50%;background:rgba(45,90,39,0.08);border:1.5px solid rgba(45,90,39,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <svg width="34" height="34" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                        <path d="M16 28C16 28 8 20 8 12a8 8 0 0 1 16 0c0 8-8 16-8 16z" fill="rgba(45,90,39,0.10)" stroke="#2D5A27" stroke-width="2" />
                        <path d="M16 28V16" stroke="#2D5A27" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M16 22c-3-3-4-6-4-10" stroke="#2D5A27" stroke-width="1.4" stroke-linecap="round" opacity="0.6" />
                        <path d="M16 22c3-3 4-6 4-10" stroke="#2D5A27" stroke-width="1.4" stroke-linecap="round" opacity="0.6" />
                    </svg>
                </div>
                <p style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.85rem;color:#1A1A2E;letter-spacing:0.06em;text-transform:uppercase;">Vegetable Forward</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <div style="width:72px;height:72px;border-radius:50%;background:rgba(180,40,40,0.07);border:1.5px solid rgba(180,40,40,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <svg width="34" height="34" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                        <path d="M11 11h10l-2.5 11h-5L11 11z" stroke="#b91c1c" stroke-width="1.8" fill="none" stroke-linejoin="round" />
                        <path d="M13.5 11V9h5v2" stroke="#b91c1c" stroke-width="1.8" stroke-linecap="round" />
                        <path d="M8 8l16 16" stroke="#b91c1c" stroke-width="2.2" stroke-linecap="round" />
                    </svg>
                </div>
                <p style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.85rem;color:#1A1A2E;letter-spacing:0.06em;text-transform:uppercase;">No Added Sugar</p>
            </div>
            <div class="flex flex-col items-center text-center gap-3">
                <div style="width:72px;height:72px;border-radius:50%;background:rgba(45,90,39,0.08);border:1.5px solid rgba(45,90,39,0.18);display:flex;align-items:center;justify-content:center;margin:0 auto;">
                    <svg width="34" height="34" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                        <path d="M16 6l9 3.5v9c0 5.5-4 10-9 11-5-1-9-5.5-9-11V9.5L16 6z" fill="rgba(45,90,39,0.08)" stroke="#2D5A27" stroke-width="2" />
                        <path d="M11.5 16l3 3 6-6" stroke="#2D5A27" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <p style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.85rem;color:#1A1A2E;letter-spacing:0.06em;text-transform:uppercase;">No Preservatives</p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3 — NUMNAM PUREES --}}
<section style="background:#F8F5F0;width:100%;padding:80px 0;position:relative;">
    <div style="position:absolute;inset:0;background-image:url('{{ asset('assets/images/bg_content.png') }}');background-size:cover;background-position:center;opacity:0.88;pointer-events:none;"></div>
    <div style="position:relative;z-index:1;width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">
        <p style="font-family:'Poppins',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#2D7C3A;margin-bottom:0.75rem;">Stage 1 &middot; 6+ Months</p>
        <h2 style="font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:800;color:#1A1A2E;text-transform:uppercase;letter-spacing:-0.01em;line-height:1.05;margin-bottom:2.5rem;">Num Nam Purees</h2>
        <div class="relative" style="padding:0 56px;">
            <button class="nn-arrow-btn" onclick="nnCarousel('puree',-1)" aria-label="Previous puree"
                style="position:absolute;left:0;top:50%;transform:translateY(-50%);">&#8249;</button>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                <div style="background:#fff;border-radius:24px;padding:36px 28px;box-shadow:0 4px 24px rgba(0,0,0,0.07);">
                    <h3 class="font-heading font-black" style="font-size:1.12rem;color:#1A1A2E;line-height:1.3;">
                        Why Parents love<br>Num Nam Purees
                    </h3>
                    <ul class="mt-5 space-y-3">
                        @foreach(['Helps introduce vegetables early','Smooth and easy to serve','Great for home or travel','No preservatives','No added sugar','No added salt'] as $pt)
                        <li class="flex items-start gap-2.5 text-sm leading-snug" style="color:#333;">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" style="flex-shrink:0;margin-top:4px;" aria-hidden="true">
                                <circle cx="6.5" cy="6.5" r="5.5" fill="#2D7C3A" opacity="0.12" />
                                <path d="M4 6.5l2 2 3.5-3" stroke="#2D7C3A" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>{{ $pt }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div style="background:#fff;border-radius:24px;padding:28px 20px;box-shadow:0 4px 24px rgba(0,0,0,0.07);text-align:center;">
                    <h3 class="font-heading font-black mb-4" style="font-size:1.1rem;color:#1A1A2E;">Purees</h3>
                    @foreach($pureeItems as $i => $item)
                    <div class="nn-puree-slide" data-idx="{{ $i }}" style="{{ $i !== 0 ? 'display:none;' : '' }}">
                        <a href="{{ route('store.product.show', $item['slug']) }}">
                            <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy"
                                style="height:200px;width:100%;object-fit:contain;display:block;">
                        </a>
                        <p class="font-heading font-bold mt-3" style="font-size:0.92rem;color:#2D5A27;">{{ $item['name'] }}</p>
                    </div>
                    @endforeach
                    <a href="{{ route('store.products') }}" class="inline-block mt-5 font-heading font-bold text-xs"
                        style="color:#2D7C3A;text-decoration:underline;text-underline-offset:3px;">Explore All Purees &rarr;</a>
                </div>
                <div style="background:#fff;border-radius:24px;padding:36px 28px;box-shadow:0 4px 24px rgba(0,0,0,0.07);">
                    <h3 class="font-heading font-black" style="font-size:1.12rem;color:#1A1A2E;line-height:1.3;">
                        What NumNam<br>Purees Are
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed" style="color:#555;">
                        NumNam Purees are smooth, easy-to-serve fruit and vegetable pouches made for babies beginning their food journey from 6+ months onward.
                    </p>
                    <ul class="mt-5 space-y-3">
                        @foreach(['Smooth texture for early feeding','Easy pouch format','Vegetable-forward combinations','Made for tiny taste explorers'] as $pt)
                        <li class="flex items-start gap-2.5 text-sm leading-snug" style="color:#333;">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" style="flex-shrink:0;margin-top:4px;" aria-hidden="true">
                                <circle cx="6.5" cy="6.5" r="5.5" fill="#2D7C3A" opacity="0.12" />
                                <path d="M4 6.5l2 2 3.5-3" stroke="#2D7C3A" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>{{ $pt }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button class="nn-arrow-btn" onclick="nnCarousel('puree',1)" aria-label="Next puree"
                style="position:absolute;right:0;top:50%;transform:translateY(-50%);">&#8250;</button>
        </div>
    </div>
</section>

{{-- SECTION 4 — NUMNAM PUFFS --}}
<section style="background:#ffffff;width:100%;padding:80px 0;position:relative;">
    <div style="position:absolute;inset:0;background-image:url('{{ asset('assets/images/bg_content.png') }}');background-size:cover;background-position:center;opacity:0.84;pointer-events:none;"></div>
    <div style="position:relative;z-index:1;width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">
        <p style="font-family:'Poppins',sans-serif;font-size:0.75rem;font-weight:700;letter-spacing:0.22em;text-transform:uppercase;color:#2D7C3A;margin-bottom:0.75rem;">Stage 2 &middot; 8+ Months</p>
        <h2 style="font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:800;color:#1A1A2E;text-transform:uppercase;letter-spacing:-0.01em;line-height:1.05;margin-bottom:2.5rem;">Num Nam Puffs</h2>
        <div class="relative" style="padding:0 56px;">
            <button class="nn-arrow-btn" onclick="nnCarousel('puff',-1)" aria-label="Previous puff"
                style="position:absolute;left:0;top:50%;transform:translateY(-50%);">&#8249;</button>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                <div style="background:#fff;border-radius:24px;padding:36px 28px;box-shadow:0 4px 24px rgba(0,0,0,0.07);">
                    <h3 class="font-heading font-black" style="font-size:1.12rem;color:#1A1A2E;line-height:1.3;">
                        Why parents choose<br>NumNam Puffs
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed" style="color:#555;">
                        Our puffs are designed to be a more thoughtful snacking option —
                    </p>
                    <ul class="mt-3 space-y-3">
                        @foreach(['combining convenience,','playful texture, and','carefully chosen ingredients in a format children enjoy.'] as $pt)
                        <li class="flex items-start gap-2.5 text-sm leading-snug" style="color:#333;">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" style="flex-shrink:0;margin-top:4px;" aria-hidden="true">
                                <circle cx="6.5" cy="6.5" r="5.5" fill="#2D7C3A" opacity="0.12" />
                                <path d="M4 6.5l2 2 3.5-3" stroke="#2D7C3A" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>{{ $pt }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div style="background:#fff;border-radius:24px;padding:28px 20px;box-shadow:0 4px 24px rgba(0,0,0,0.07);text-align:center;">
                    <h3 class="font-heading font-black mb-4" style="font-size:1.1rem;color:#1A1A2E;">Puffs</h3>
                    @foreach($puffItems as $i => $item)
                    <div class="nn-puff-slide" data-idx="{{ $i }}" style="{{ $i !== 0 ? 'display:none;' : '' }}">
                        <a href="{{ route('store.product.show', $item['slug']) }}">
                            <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy"
                                style="height:200px;width:100%;object-fit:contain;display:block;">
                        </a>
                        <p class="font-heading font-bold mt-3" style="font-size:0.92rem;color:#2D5A27;">{{ $item['name'] }}</p>
                    </div>
                    @endforeach
                    <a href="{{ route('store.products') }}" class="inline-block mt-5 font-heading font-bold text-xs"
                        style="color:#2D7C3A;text-decoration:underline;text-underline-offset:3px;">Explore All Puffs &rarr;</a>
                </div>
                <div style="background:#fff;border-radius:24px;padding:36px 28px;box-shadow:0 4px 24px rgba(0,0,0,0.07);">
                    <h3 class="font-heading font-black" style="font-size:1.12rem;color:#1A1A2E;line-height:1.3;">
                        What they Are
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed" style="color:#555;">
                        NumNam Puffs are light, easy-to-hold finger snacks created for babies and young children as they begin self-feeding and texture exploration.
                    </p>
                    <ul class="mt-5 space-y-3">
                        @foreach(['Easy for little hands to hold','Gentle crunchy texture','Made for snack time and on-the-go','Designed for growing kids'] as $pt)
                        <li class="flex items-start gap-2.5 text-sm leading-snug" style="color:#333;">
                            <svg width="13" height="13" viewBox="0 0 13 13" fill="none" style="flex-shrink:0;margin-top:4px;" aria-hidden="true">
                                <circle cx="6.5" cy="6.5" r="5.5" fill="#2D7C3A" opacity="0.12" />
                                <path d="M4 6.5l2 2 3.5-3" stroke="#2D7C3A" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>{{ $pt }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button class="nn-arrow-btn" onclick="nnCarousel('puff',1)" aria-label="Next puff"
                style="position:absolute;right:0;top:50%;transform:translateY(-50%);">&#8250;</button>
        </div>
    </div>
</section>

{{-- SECTION 5 — 2x2 GRID --}}
<section style="background:#F8F5F0;width:100%;padding:80px 0;">
    <div style="width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            {{-- Parent Favourites --}}
            <div style="background:#F9F5EE;border-radius:20px;padding:32px;">
                <h3 class="font-heading font-black mb-6" style="font-size:1.2rem;color:#1A1A2E;">Parent favourites</h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach($favItems as $fav)
                    <div style="background:#fff;border-radius:14px;padding:14px 10px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                        <img src="{{ $fav['img'] }}" alt="{{ $fav['name'] }}" loading="lazy"
                            style="height:72px;width:100%;object-fit:contain;margin-bottom:8px;">
                        <p class="font-heading font-bold" style="font-size:0.72rem;color:#1A1A2E;line-height:1.3;">{{ $fav['name'] }}</p>
                        <a href="{{ route('store.product.show', $fav['slug']) }}"
                            class="inline-block mt-2 font-heading font-bold text-white rounded-full"
                            style="background:#2D5A27;font-size:0.65rem;padding:5px 10px;">
                            Add to Cart &rsaquo;
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Make feeding easier --}}
            <div style="background:#EEF4EC;border-radius:20px;padding:32px;position:relative;overflow:hidden;">
                <div style="position:relative;z-index:1;">
                    <h3 class="font-heading font-black mb-3" style="font-size:1.2rem;color:#1A1A2E;line-height:1.3;">
                        Make feeding easier <span style="color:#2D7C3A;">With NumNam</span>
                    </h3>
                    <p class="text-sm leading-relaxed mb-6" style="color:#555;max-width:320px;">
                        NumNam was created by doctor-parents who wanted kids' food that draws from the best of European nutrition — practical, vegetable-rich, and designed for real families.
                    </p>
                    <a href="{{ route('store.about') }}"
                        style="display:inline-flex;align-items:center;background:#2D5A27;color:#fff;font-family:inherit;font-size:0.85rem;font-weight:700;padding:11px 22px;border-radius:8px;text-decoration:none;">
                        Visit the Learn Section
                    </a>
                </div>
            </div>

            {{-- How NumNam began --}}
            <div style="background:#F9F5EE;border-radius:20px;padding:32px;">
                <h3 class="font-heading font-black mb-3" style="font-size:1.2rem;color:#1A1A2E;">How NumNam began</h3>
                <p class="text-sm leading-relaxed mb-6" style="color:#555;max-width:340px;">
                    NumNam was created by doctor-parents who wanted the best of choices in baby food inspired from Germany, but viable and friendly for every family.
                </p>
                <a href="{{ route('store.about') }}"
                    style="display:inline-flex;align-items:center;background:#fff;color:#1A1A2E;font-family:inherit;font-size:0.85rem;font-weight:700;padding:11px 22px;border-radius:8px;text-decoration:none;border:1.5px solid #1A1A2E;">
                    Read Our Story
                </a>
            </div>

            {{-- Guidance for every feeding stage --}}
            <div style="background:#EEF4EC;border-radius:20px;padding:32px;position:relative;overflow:hidden;">
                <div style="position:relative;z-index:1;">
                    <h3 class="font-heading font-black mb-3" style="font-size:1.2rem;color:#1A1A2E;line-height:1.3;">
                        Guidance for every<br>feeding stage
                    </h3>
                    <p class="text-sm leading-relaxed mb-6" style="color:#555;max-width:320px;">
                        Explore feeding guidance: stage-by-stage topics, the foods that help most, and how NumNam fits into healthy eating habits for your little one.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('store.products') }}"
                            style="display:inline-flex;align-items:center;background:#2D5A27;color:#fff;font-family:inherit;font-size:0.85rem;font-weight:700;padding:10px 20px;border-radius:8px;text-decoration:none;">
                            View Store
                        </a>
                        <a href="{{ route('store.blog.index') }}"
                            style="display:inline-flex;align-items:center;background:#fff;color:#1A1A2E;font-family:inherit;font-size:0.85rem;font-weight:700;padding:10px 20px;border-radius:8px;text-decoration:none;border:1.5px solid #1A1A2E;">
                            Learn More &rsaquo;
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SECTION 6 — PRODUCT TABS --}}
<section style="position:relative;width:100%;padding:100px 0;overflow:hidden;">
    <div style="position:absolute;inset:0;background-image:url('{{ asset('assets/images/bg_products.png') }}');background-size:cover;background-position:center;"></div>
    <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,25,12,0.75) 0%,rgba(10,25,12,0.68) 100%);"></div>
    <div style="position:relative;z-index:10;width:100%;max-width:1400px;margin:0 auto;padding:0 clamp(1.5rem,5vw,3rem);">

        {{-- Header --}}
        <div class="text-center mb-12">
            <p style="font-family:'Poppins',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:0.24em;text-transform:uppercase;color:rgba(180,240,180,0.80);margin-bottom:0.9rem;">Explore Our Range</p>
            <h2 style="font-family:'Poppins',sans-serif;font-size:clamp(1.8rem,3.5vw,3rem);font-weight:800;color:#ffffff;text-transform:uppercase;letter-spacing:-0.01em;line-height:1.05;">All Products</h2>
        </div>

        {{-- Tabs --}}
        <div class="flex justify-center mb-10" style="gap:0.5rem;flex-wrap:wrap;">
            <button onclick="nnTab('purees')" id="tab-purees"
                style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);">
                Purees
            </button>
            <button onclick="nnTab('puffs')" id="tab-puffs"
                style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;cursor:pointer;transition:all 0.2s;background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);">
                Puffs
            </button>
            <a href="{{ route('store.products') }}"
                style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.78rem;letter-spacing:0.12em;text-transform:uppercase;padding:10px 28px;border-radius:100px;transition:all 0.2s;background:#ffffff;color:#1A1A2E;border:2px solid #ffffff;text-decoration:none;display:inline-block;">
                All
            </a>
        </div>

        {{-- Purees Tab --}}
        <div id="tabpanel-purees" class="grid grid-cols-2 gap-5 md:grid-cols-4" style="display:none;">
            @foreach($pureeItems as $item)
            <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy"
                    style="height:180px;width:100%;object-fit:contain;display:block;">
            </a>
            @endforeach
        </div>

        {{-- Puffs Tab --}}
        <div id="tabpanel-puffs" class="grid grid-cols-2 gap-5 md:grid-cols-4" style="display:none;">
            @foreach($puffItems as $item)
            <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy"
                    style="height:180px;width:100%;object-fit:contain;display:block;">
            </a>
            @endforeach
        </div>

        {{-- All Tab (shown by default) --}}
        <div id="tabpanel-all" class="grid grid-cols-2 gap-5 md:grid-cols-4">
            @foreach(array_merge($pureeItems, $puffItems) as $item)
            <a href="{{ route('store.product.show', $item['slug']) }}" style="display:block;text-decoration:none;">
                <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" loading="lazy"
                    style="height:180px;width:100%;object-fit:contain;display:block;">
            </a>
            @endforeach
        </div>

    </div>
</section>

@endsection

@section('scripts')
<style>
    .nn-hero-btn {
        transition: transform 0.18s, box-shadow 0.18s;
    }

    .nn-hero-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(45, 90, 39, 0.38) !important;
    }

    .nn-arrow-btn {
        background: #F07AA2;
        border: none;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1.6rem;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(240, 122, 162, 0.40);
        z-index: 5;
        transition: background 0.2s;
        line-height: 1;
    }

    .nn-arrow-btn:hover {
        background: #d95e8c;
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

    // Product Tabs
    (function() {
        var tabs = ['purees', 'puffs'];
        var activeStyle = 'background:#ffffff;color:#1A1A2E;border:2px solid #ffffff;';
        var inactiveStyle = 'background:transparent;color:rgba(255,255,255,0.70);border:2px solid rgba(255,255,255,0.35);';
        window.nnTab = function(active) {
            // Hide all-panel when a specific tab is selected
            var allPanel = document.getElementById('tabpanel-all');
            if (allPanel) allPanel.style.display = 'none';
            tabs.forEach(function(t) {
                var btn = document.getElementById('tab-' + t);
                var panel = document.getElementById('tabpanel-' + t);
                if (!btn || !panel) return;
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