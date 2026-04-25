<header class="site-header" id="siteHeader">
    <div class="site-header-inner">
        {{-- Logo --}}
        <a href="{{ route('store.home') }}" class="brand" aria-label="NumNam Home">
            <img src="{{ asset('assets/images/Logo/TM.png') }}" alt="NumNam logo" width="80" height="80" loading="lazy" class="brand-logo-img">
        </a>

        {{-- Right actions: search + cart + hamburger --}}
        <div class="header-actions">
            <button type="button" class="header-icon-btn" data-search-toggle aria-label="Search">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>

            <a href="{{ route('store.cart') }}" class="header-icon-btn cart-icon-link {{ request()->routeIs('store.cart') ? 'active' : '' }}" aria-label="Cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h7.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                @if(($cartItemCount ?? 0) > 0)
                <span class="number-tag">{{ $cartItemCount }}</span>
                @endif
            </a>

            {{-- Hamburger button --}}
            <button type="button" class="hamburger-btn" id="hamburgerBtn"
                aria-label="Open navigation menu" aria-expanded="false" aria-controls="nnFullscreenMenu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </div>

    {{-- Search overlay --}}
    <div class="search-overlay" id="searchOverlay" hidden>
        <form method="GET" action="{{ route('store.products') }}" class="search-overlay-form" data-search-form data-suggest-url="{{ route('store.search.suggestions') }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" name="q" placeholder="Search products, recipes, articles..." autocomplete="off" class="search-overlay-input" data-search-input value="{{ request('q') }}">
            <button type="button" class="search-overlay-close" data-search-close aria-label="Close search">&times;</button>
            <div class="search-suggest-box hidden" data-search-results></div>
        </form>
    </div>
</header>

{{-- Fullscreen overlay menu --}}
<div class="nn-fullscreen-menu" id="nnFullscreenMenu" aria-hidden="true">
    <div class="nn-fullscreen-menu__inner">

        {{-- Left: utility / footer links --}}
        <div class="nn-menu-foot">
            <a href="{{ route('store.blog.index') }}">Blog</a>
            <a href="{{ route('store.recipes') }}">Recipes</a>
            <a href="{{ route('store.faq') }}">FAQ</a>
            <a href="{{ route('store.contact') }}">Contact</a>
            @auth
            <a href="{{ route('store.account') }}">My Account</a>
            @else
            <a href="{{ route('store.login') }}">Login</a>
            <a href="{{ route('store.register') }}">Register</a>
            @endauth
            <span class="nn-menu-copyright">&copy; {{ date('Y') }} NumNam</span>
        </div>

        {{-- Center: main nav links --}}
        <nav class="nn-menu-main" role="navigation" aria-label="Main menu">
            <a href="{{ route('store.home') }}" class="nn-menu-link {{ request()->routeIs('store.home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('store.products') }}" class="nn-menu-link {{ request()->routeIs('store.products*') ? 'active' : '' }}">Shop</a>
            <a href="{{ route('store.pricing') }}" class="nn-menu-link {{ request()->routeIs('store.pricing*') ? 'active' : '' }}">Subscriptions</a>
            <a href="{{ route('store.blog.index') }}" class="nn-menu-link {{ request()->routeIs('store.blog*') ? 'active' : '' }}">Learn</a>
            <a href="{{ route('store.about') }}" class="nn-menu-link {{ request()->routeIs('store.about') ? 'active' : '' }}">About</a>
        </nav>

        {{-- Right: social links --}}
        <div class="nn-menu-social">
            <a href="https://www.instagram.com/numnam_baby" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                    <circle cx="12" cy="12" r="4" />
                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                </svg>
            </a>
            <a href="https://www.facebook.com/numnam" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                </svg>
            </a>
            <a href="https://twitter.com/numnam_baby" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                </svg>
            </a>
        </div>

    </div>
</div>