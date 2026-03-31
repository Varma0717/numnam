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
    <meta name="theme-color" content="#195b48">
    <meta name="msapplication-TileColor" content="#195b48">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NumNam">
    <meta name="application-name" content="NumNam">
    <meta name="twitter:description" content="@yield('meta_description', 'Clean-label baby nutrition for modern families.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/hero.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/images/favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon.png') }}">
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="{{ request()->routeIs('store.home') ? 'store-home' : 'store-inner' }}">
    {{-- Skip to content - Accessibility --}}
    <a href="#main-content" class="skip-link">Skip to content</a>

    <div class="page-shell">
        {{-- Announcement Bar --}}
        @include('store.partials.announcement-bar')

        @include('store.partials.header')

        <main id="main-content" class="page" role="main">
            {{-- Inner page banner (shown on all non-home pages) --}}
            @unless(request()->routeIs('store.home') || request()->routeIs('store.product.show'))
            @php
            $innerBannerMap = [
            'store.products' => 'Shop All Products',
            'store.about' => 'About NumNam',
            'store.blog.index' => 'Blog & Updates',
            'store.blog.show' => 'Blog',
            'store.contact' => 'Get In Touch',
            'store.faq' => 'Help & FAQ',
            'store.pricing' => 'Subscription Plans',
            'store.cart' => 'Your Cart',
            'store.checkout' => 'Checkout',
            'store.recipes' => 'Baby-Friendly Recipes',
            'store.account' => 'My Account',
            'store.login' => 'Sign In',
            'store.register' => 'Get Started',
            'store.refer-friends' => 'Refer & Earn',
            'store.wishlist' => 'My Wishlist',
            'store.order-success' => 'Order Confirmed',
            'store.category.show' => 'Category',
            'store.legal.terms' => 'Terms & Conditions',
            'store.legal.privacy' => 'Privacy Policy',
            'store.legal.shipping' => 'Shipping Policy',
            'store.legal.refund' => 'Refund Policy',
            'store.legal.cookie' => 'Cookie Policy',
            'store.legal.payment-methods' => 'Payment Methods',
            'store.page' => 'NumNam',
            ];
            $currentRoute = Route::currentRouteName();
            $innerBannerTitle = $innerBannerMap[$currentRoute] ?? null;
            if (!$innerBannerTitle && $currentRoute) {
            // Derive human-readable title from route name as fallback
            $parts = explode('.', $currentRoute);
            $innerBannerTitle = ucwords(str_replace(['-', '_'], ' ', end($parts)));
            }
            @endphp
            <div class="inner-banner">
                <div class="inner-banner-overlay"></div>
                {{-- Decorative product image right side --}}
                <div class="pointer-events-none hidden lg:block" style="position:absolute;right:0;top:0;bottom:0;width:260px;overflow:hidden;opacity:0.18;">
                    <img src="{{ asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg') }}" alt="" style="height:100%;width:100%;object-fit:contain;object-position:right center;transform:rotate(10deg) scale(1.1);">
                </div>
                <div class="inner-banner-content">
                    <h1 class="inner-banner-title">{{ $innerBannerTitle }}</h1>
                    @include('store.partials.breadcrumbs')
                </div>
            </div>
            @endunless
            {{-- Breadcrumbs are shown in the inner-banner above (for inner pages). Product show has inline breadcrumbs. --}}

            @include('store.partials.alerts')
            @yield('content')
        </main>

        @include('store.partials.footer')
    </div>

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