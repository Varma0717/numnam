<header class="site-header">
    <div class="site-header-inner">
        <a href="{{ route('store.home') }}" class="brand">
            <span class="brand-dot">
                <img src="{{ asset('assets/images/Logo/TM.png') }}" alt="NumNam logo" width="22" height="22" loading="lazy">
            </span>
            <span>NumNam</span>
        </a>

        <button type="button" class="nav-toggle" data-nav-toggle aria-label="Toggle navigation">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>

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

        <div class="site-actions">
            <a href="tel:+919014252278" class="hidden lg:inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900" aria-label="Call +91 90142 52278">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                </svg>
                <span>+91 90142 52278</span>
            </a>
            <a href="mailto:info@numnam.com" class="hidden xl:inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900" aria-label="Email info@numnam.com">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <path d="M22 7l-10 7L2 7" />
                </svg>
                <span>info@numnam.com</span>
            </a>

            {{-- Search toggle --}}
            <button type="button" class="header-icon-btn" data-search-toggle aria-label="Search">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>

            {{-- Cart icon --}}
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

            {{-- Account --}}
            @auth
            <a class="header-icon-btn" href="{{ route('store.account') }}" aria-label="My Account">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </a>
            <form method="POST" action="{{ route('store.logout') }}" class="inline-form">
                @csrf
                <button type="submit" class="cta-btn cta-btn-sm">Logout</button>
            </form>
            @else
            <a class="btn-ghost btn-ghost-sm" href="{{ route('store.login') }}">Login</a>
            <a class="cta-btn cta-btn-sm" href="{{ route('store.register') }}">Register</a>
            @endauth
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
        <a href="tel:+919014252278">Call: +91 90142 52278</a>
        <a href="https://wa.me/919014252278" target="_blank" rel="noopener noreferrer">WhatsApp Support</a>
        <a href="mailto:info@numnam.com">Email: info@numnam.com</a>
        <a href="{{ route('store.cart') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:4px">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h7.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            Cart ({{ $cartItemCount ?? 0 }})
        </a>
        @auth
        <a href="{{ route('store.account') }}">My Account</a>
        @else
        <a href="{{ route('store.login') }}">Login</a>
        <a href="{{ route('store.register') }}">Register</a>
        @endauth
    </nav>
</header>