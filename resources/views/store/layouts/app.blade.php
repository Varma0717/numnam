<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NumNam | Doctor-Founded Baby Nutrition')</title>
    <meta name="description" content="@yield('meta_description', 'NumNam delivers doctor-founded, clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients for modern families.')">
    <meta name="keywords" content="baby food, baby nutrition, infant food, organic baby food, stage-wise nutrition, baby food subscription, NumNam">
    <meta name="author" content="NumNam">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NumNam">
    <meta property="og:title" content="@yield('title', 'NumNam | Doctor-Founded Baby Nutrition')">
    <meta property="og:description" content="@yield('meta_description', 'Clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/hero.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'NumNam | Doctor-Founded Baby Nutrition')">

    {{-- Browser appearance --}}
    <meta name="theme-color" content="#FF6B8A">
    <meta name="msapplication-TileColor" content="#FF6B8A">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NumNam">
    <meta name="application-name" content="NumNam">
    <meta name="twitter:description" content="@yield('meta_description', 'Clean-label baby nutrition for modern families.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/hero.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/Logo/TM.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/Logo/TM.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/Logo/TM.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "NumNam",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('assets/images/Logo/TM.png') }}",
            "description": "Doctor-founded baby nutrition platform with clean ingredients, subscriptions, and parent education content.",
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+91-9014252278",
                "contactType": "customer service",
                "email": "info@numnam.com"
            },
            "sameAs": [
                "https://www.instagram.com/numnam_baby",
                "https://www.facebook.com/numnam",
                "https://twitter.com/numnam_baby"
            ]
        }
    </script>
    @yield('structured_data')

    {{-- Preconnect for performance --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <meta name="asset-base" content="{{ rtrim(url(''), '/') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ url('assets/store/css/components/header.css') }}">
    @yield('head')
</head>

<body class="{{ request()->routeIs('store.home') ? 'store-home' : 'store-inner' }}">
    {{-- Skip to content - Accessibility --}}
    <a href="#main-content" class="skip-link">Skip to content</a>

    <div class="page-shell">

        @include('store.partials.header')

        <main id="main-content" class="page" role="main">
            {{-- Breadcrumbs (excluded on home + product detail — product show places them inline) --}}
            @unless(request()->routeIs('store.home') || request()->routeIs('store.product.show'))
            @include('store.partials.breadcrumbs')
            @endunless

            @include('store.partials.alerts')
            @yield('content')
        </main>

        @include('store.partials.footer')
    </div>

    <nav class="mobile-app-nav" aria-label="Mobile quick navigation">
        <a href="{{ route('store.home') }}" class="{{ request()->routeIs('store.home') ? 'active' : '' }}" aria-current="{{ request()->routeIs('store.home') ? 'page' : 'false' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Home
        </a>
        <a href="{{ route('store.products') }}" class="{{ request()->routeIs('store.products*') || request()->routeIs('store.product.show') ? 'active' : '' }}" aria-current="{{ request()->routeIs('store.products*') || request()->routeIs('store.product.show') ? 'page' : 'false' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 01-8 0" />
            </svg>
            Shop
        </a>
        <a href="{{ route('store.cart') }}" class="{{ request()->routeIs('store.cart') || request()->routeIs('store.checkout') ? 'active' : '' }}" aria-current="{{ request()->routeIs('store.cart') || request()->routeIs('store.checkout') ? 'page' : 'false' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h7.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            Cart
        </a>
        @auth
        <a href="{{ route('store.account') }}" class="{{ request()->routeIs('store.account') ? 'active' : '' }}" aria-current="{{ request()->routeIs('store.account') ? 'page' : 'false' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Account
        </a>
        @else
        <a href="{{ route('store.login') }}" class="{{ request()->routeIs('store.login') || request()->routeIs('store.register') ? 'active' : '' }}" aria-current="{{ request()->routeIs('store.login') || request()->routeIs('store.register') ? 'page' : 'false' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Account
        </a>
        @endauth
    </nav>

    {{-- Back to top button --}}
    <button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="18 15 12 9 6 15" />
        </svg>
    </button>

    {{-- Cookie Consent --}}
    @include('store.partials.cookie-consent')

    {{-- Sticky contact bar + floating WhatsApp --}}
    @include('store.partials.contact-actions')

    {{-- First-time discount popup --}}
    @include('store.partials.discount-popup')

    {{-- Toast notifications container --}}
    <div id="toast-container" class="toast-container" aria-live="polite"></div>

    <script src="{{ url('assets/store/js/components/header.js') }}" defer></script>
    <script src="{{ url('assets/store/js/store.js') }}" defer></script>
    @yield('scripts')
</body>

</html>