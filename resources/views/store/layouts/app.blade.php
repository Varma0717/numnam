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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="{{ request()->routeIs('store.home') ? 'store-home' : 'store-inner' }}">
    {{-- Skip to content - Accessibility --}}
    <a href="#main-content" class="skip-link">Skip to content</a>

    <div class="page-shell">
        <div class="kids-theme-layer" aria-hidden="true">
            <img src="{{ asset('assets/images/kids-icons/banner-bg-shape-1.png') }}" alt="" class="kids-shape kids-shape-1" loading="lazy">
            <img src="{{ asset('assets/images/kids-icons/value-shape-2.png') }}" alt="" class="kids-shape kids-shape-2" loading="lazy">
            <img src="{{ asset('assets/images/kids-icons/bird_2.png') }}" alt="" class="kids-shape kids-shape-3" loading="lazy">
            <img src="{{ asset('assets/images/kids-icons/twobirds_1.png') }}" alt="" class="kids-shape kids-shape-4" loading="lazy">
            <img src="{{ asset('assets/images/kids-icons/animated_animals.png') }}" alt="" class="kids-shape kids-shape-5" loading="lazy">
        </div>

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