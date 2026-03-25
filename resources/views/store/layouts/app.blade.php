<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NumNam – Doctor-Founded Baby Nutrition')</title>
    <meta name="description" content="@yield('meta_description', 'NumNam delivers doctor-founded, clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients for modern families.')">
    <meta name="keywords" content="baby food, baby nutrition, infant food, organic baby food, stage-wise nutrition, baby food subscription, NumNam">
    <meta name="author" content="NumNam">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NumNam">
    <meta property="og:title" content="@yield('title', 'NumNam – Doctor-Founded Baby Nutrition')">
    <meta property="og:description" content="@yield('meta_description', 'Clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients.')">
    <meta property="og:image" content="@yield('og_image', asset('assets/images/hero.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'NumNam – Doctor-Founded Baby Nutrition')">
    <meta name="twitter:description" content="@yield('meta_description', 'Clean-label baby nutrition for modern families.')">
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/hero.jpg'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/favicon.png') }}">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "NumNam",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('assets/images/logo.png') }}",
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Lobster+Two:wght@400;700&family=Mulish:wght@400;700&display=swap">

    <link rel="stylesheet" href="{{ url('assets/store/css/base.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/header.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/footer.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/cards.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/components/forms.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/home.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/catalog.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/checkout.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/product-detail.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/blog.css') }}">
    <link rel="stylesheet" href="{{ url('assets/store/css/pages/pages.css') }}">
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
            {{-- Breadcrumbs --}}
            @unless(request()->routeIs('store.home'))
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

    {{-- Toast notifications container --}}
    <div id="toast-container" class="toast-container" aria-live="polite"></div>

    <script src="{{ url('assets/store/js/components/header.js') }}" defer></script>
    <script src="{{ url('assets/store/js/store.js') }}" defer></script>
    @yield('scripts')
</body>

</html>