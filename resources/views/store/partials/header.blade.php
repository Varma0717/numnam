<header class="site-header">
    {{-- Row 1: Top bar with contact info --}}
    <div class="header-row header-row-top">
        <div class="header-wrap header-top-inner">
            <div class="header-top-left">
                <a href="tel:+919014252278" class="header-top-link">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.88.36 1.74.7 2.56a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.82.34 1.68.57 2.56.7A2 2 0 0122 16.92z" />
                    </svg>
                    +91 90142 52278
                </a>
                <a href="mailto:info@numnam.com" class="header-top-link">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    info@numnam.com
                </a>
            </div>
            <div class="header-top-right">
                <a href="{{ route('store.blog.index') }}" class="header-top-link">Blog</a>
                <a href="{{ route('store.faq') }}" class="header-top-link">FAQ</a>
                <a href="{{ route('store.contact') }}" class="header-top-link">Contact</a>
                @auth
                <a href="{{ route('store.account') }}" class="header-top-link">My Account</a>
                @else
                <a href="{{ route('store.login') }}" class="header-top-link">Login</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="header-row header-row-main">
        <div class="header-wrap header-main-inner">
            <button type="button" class="nav-toggle" data-nav-toggle aria-label="Toggle navigation">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>

            <a href="{{ route('store.home') }}" class="brand" aria-label="NumNam Home">
                <img src="{{ asset('assets/images/Logo/TM.png') }}" alt="NumNam logo" width="75" height="75" loading="lazy" class="brand-logo-img">
            </a>

            <form method="GET" action="{{ route('store.products') }}" class="header-search" data-search-form data-suggest-url="{{ route('store.search.suggestions') }}">
                <input type="search" name="q" placeholder="Search products, recipes, articles..." autocomplete="off" class="header-search-input" data-search-input value="{{ request('q') }}">
                <button type="submit" class="header-search-submit" aria-label="Search">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </button>
                <div class="search-suggest-box hidden" data-search-results></div>
            </form>

            <div class="site-actions">
                <button type="button" class="header-icon-btn md:hidden" data-search-toggle aria-label="Search">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </button>

                <a href="{{ route('store.cart') }}" class="header-icon-btn cart-icon-link {{ request()->routeIs('store.cart') ? 'active' : '' }}" aria-label="Cart">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h7.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    @if(($cartItemCount ?? 0) > 0)
                    <span class="number-tag">{{ $cartItemCount }}</span>
                    @endif
                </a>

                @auth
                <a class="header-icon-btn" href="{{ route('store.account') }}" aria-label="My Account">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </a>
                <form method="POST" action="{{ route('store.logout') }}" class="inline-form hidden lg:inline-flex">
                    @csrf
                    <button type="submit" class="cta-btn cta-btn-sm">Logout</button>
                </form>
                @else
                <a class="btn-ghost btn-ghost-sm hidden lg:inline-flex" href="{{ route('store.login') }}">Login</a>
                <a class="cta-btn cta-btn-sm hidden lg:inline-flex" href="{{ route('store.register') }}">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="header-row header-row-nav">
        <div class="header-wrap">
            <nav class="site-nav" role="navigation" aria-label="Main navigation">
                <a href="{{ route('store.home') }}" class="{{ request()->routeIs('store.home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('store.products') }}" class="{{ request()->routeIs('store.products*') ? 'active' : '' }}">Shop</a>
                <a href="{{ route('store.pricing') }}" class="{{ request()->routeIs('store.pricing*') ? 'active' : '' }}">Subscriptions</a>
                <div class="nav-item has-submenu">
                    <a href="{{ route('store.blog.index') }}" class="{{ request()->routeIs('store.blog*') || request()->routeIs('store.recipes') || request()->routeIs('store.faq') || request()->routeIs('store.refer-friends') ? 'active' : '' }}">Learn</a>
                    <ul class="submenu" role="menu" aria-label="Learn submenu">
                        <li><a href="{{ route('store.blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('store.recipes') }}">Recipes</a></li>
                        <li><a href="{{ route('store.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('store.refer-friends') }}">Refer Friends</a></li>
                    </ul>
                </div>
                <a href="{{ route('store.about') }}" class="{{ request()->routeIs('store.about') ? 'active' : '' }}">About</a>
                <a href="{{ route('store.contact') }}" class="{{ request()->routeIs('store.contact') ? 'active' : '' }}">Contact</a>
            </nav>
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

    {{-- Mobile nav --}}
    <nav class="mobile-nav" data-mobile-nav aria-label="Mobile navigation">
        <form method="GET" action="{{ route('store.products') }}" class="mobile-search-form" data-search-form data-suggest-url="{{ route('store.search.suggestions') }}">
            <input type="search" name="q" placeholder="Search products..." class="input" data-search-input value="{{ request('q') }}">
            <div class="search-suggest-box hidden" data-search-results></div>
        </form>
        <a href="{{ route('store.home') }}">Home</a>
        <a href="{{ route('store.products') }}">Shop</a>
        <a href="{{ route('store.pricing') }}">Subscriptions</a>
        <a href="{{ route('store.blog.index') }}">Blog</a>
        <a href="{{ route('store.recipes') }}">Recipes</a>
        <a href="{{ route('store.faq') }}">FAQ</a>
        <a href="{{ route('store.refer-friends') }}">Refer Friends</a>
        <a href="{{ route('store.about') }}">About</a>
        <a href="{{ route('store.contact') }}">Contact</a>

        @auth
        <a href="{{ route('store.account') }}">My Account</a>
        @else
        <a href="{{ route('store.login') }}">Login</a>
        <a href="{{ route('store.register') }}">Register</a>
        @endauth
    </nav>
</header>