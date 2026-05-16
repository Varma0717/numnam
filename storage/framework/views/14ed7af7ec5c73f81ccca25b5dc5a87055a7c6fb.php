<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'NumNam | Doctor-Founded Baby Nutrition'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'NumNam delivers doctor-founded, clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients for modern families.'); ?>">
    <meta name="keywords" content="baby food, baby nutrition, infant food, organic baby food, stage-wise nutrition, baby food subscription, NumNam">
    <meta name="author" content="NumNam">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">

    
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NumNam">
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'NumNam | Doctor-Founded Baby Nutrition'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', 'Clean-label baby nutrition with stage-wise foods, subscriptions and transparent ingredients.'); ?>">
    <meta property="og:image" content="<?php echo $__env->yieldContent('og_image', asset('assets/images/hero.jpg')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">

    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title', 'NumNam | Doctor-Founded Baby Nutrition'); ?>">

    
    <meta name="theme-color" content="#FF6B8A">
    <meta name="msapplication-TileColor" content="#FF6B8A">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="NumNam">
    <meta name="application-name" content="NumNam">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description', 'Clean-label baby nutrition for modern families.'); ?>">
    <meta name="twitter:image" content="<?php echo $__env->yieldContent('og_image', asset('assets/images/hero.jpg')); ?>">

    
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/images/Logo/TM.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/images/Logo/TM.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/images/Logo/TM.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">

    
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "NumNam",
            "url": "<?php echo e(url('/')); ?>",
            "logo": "<?php echo e(asset('assets/images/Logo/TM.png')); ?>",
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
    <?php echo $__env->yieldContent('structured_data'); ?>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap">

    <meta name="asset-base" content="<?php echo e(rtrim(url(''), '/')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/store/css/components/header.css')); ?>?v=<?php echo e(filemtime(public_path('assets/store/css/components/header.css'))); ?>">
    <?php echo $__env->yieldContent('head'); ?>
</head>

<body class="<?php echo e(request()->routeIs('store.home') ? 'store-home' : 'store-inner'); ?>">
    
    <a href="#main-content" class="skip-link">Skip to content</a>

    <div class="page-shell">

        <?php echo $__env->make('store.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <main id="main-content" class="page" role="main">
            
            <?php if (! (request()->routeIs('store.home') || request()->routeIs('store.product.show'))): ?>
            <?php echo $__env->make('store.partials.breadcrumbs', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <?php echo $__env->make('store.partials.alerts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <?php if (! (request()->routeIs('store.home'))): ?>
        <?php echo $__env->make('store.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>

    <nav class="mobile-app-nav" aria-label="Mobile quick navigation">
        <a href="<?php echo e(route('store.home')); ?>" class="<?php echo e(request()->routeIs('store.home') ? 'active' : ''); ?>" aria-current="<?php echo e(request()->routeIs('store.home') ? 'page' : 'false'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Home
        </a>
        <a href="<?php echo e(route('store.products')); ?>" class="<?php echo e(request()->routeIs('store.products*') || request()->routeIs('store.product.show') ? 'active' : ''); ?>" aria-current="<?php echo e(request()->routeIs('store.products*') || request()->routeIs('store.product.show') ? 'page' : 'false'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 01-8 0" />
            </svg>
            Shop
        </a>
        <a href="<?php echo e(route('store.cart')); ?>" class="<?php echo e(request()->routeIs('store.cart') || request()->routeIs('store.checkout') ? 'active' : ''); ?>" aria-current="<?php echo e(request()->routeIs('store.cart') || request()->routeIs('store.checkout') ? 'page' : 'false'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h7.72a2 2 0 002-1.61L23 6H6" />
            </svg>
            Cart
        </a>
        <?php if(auth()->guard()->check()): ?>
        <a href="<?php echo e(route('store.account')); ?>" class="<?php echo e(request()->routeIs('store.account') ? 'active' : ''); ?>" aria-current="<?php echo e(request()->routeIs('store.account') ? 'page' : 'false'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Account
        </a>
        <?php else: ?>
        <a href="<?php echo e(route('store.login')); ?>" class="<?php echo e(request()->routeIs('store.login') || request()->routeIs('store.register') ? 'active' : ''); ?>" aria-current="<?php echo e(request()->routeIs('store.login') || request()->routeIs('store.register') ? 'page' : 'false'); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Account
        </a>
        <?php endif; ?>
    </nav>

    
    <button type="button" class="back-to-top" id="backToTop" aria-label="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="18 15 12 9 6 15" />
        </svg>
    </button>

    
    <?php echo $__env->make('store.partials.cookie-consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('store.partials.contact-actions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('store.partials.discount-popup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div id="toast-container" class="toast-container" aria-live="polite"></div>

    <script src="<?php echo e(asset('assets/store/js/components/header.js')); ?>?v=<?php echo e(filemtime(public_path('assets/store/js/components/header.js'))); ?>" defer></script>
    <script src="<?php echo e(asset('assets/store/js/store.js')); ?>?v=<?php echo e(filemtime(public_path('assets/store/js/store.js'))); ?>" defer></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\numnam-api\resources\views/store/layouts/app.blade.php ENDPATH**/ ?>