<header class="site-header" id="siteHeader">
    <div class="site-header-inner">
        
        <a href="<?php echo e(route('store.home')); ?>" class="brand" aria-label="NumNam Home">
            <img src="<?php echo e(asset('assets/images/Logo/TM.png')); ?>" alt="NumNam logo" width="90" height="90" loading="lazy" class="brand-logo-img">
        </a>

        
        <div class="header-actions">
            <button type="button" class="header-icon-btn" data-search-toggle aria-label="Search">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>

            <a href="<?php echo e(route('store.cart')); ?>" class="header-icon-btn cart-icon-link <?php echo e(request()->routeIs('store.cart') ? 'active' : ''); ?>" aria-label="Cart">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1" />
                    <circle cx="20" cy="21" r="1" />
                    <path d="M1 1h4l2.68 13.39a2 2 0 002 1.61h7.72a2 2 0 002-1.61L23 6H6" />
                </svg>
                <?php if(($cartItemCount ?? 0) > 0): ?>
                <span class="number-tag"><?php echo e($cartItemCount); ?></span>
                <?php endif; ?>
            </a>

            
            <button type="button" class="hamburger-btn" id="hamburgerBtn"
                aria-label="Open navigation menu" aria-expanded="false" aria-controls="nnFullscreenMenu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </div>

    
    <div class="search-overlay" id="searchOverlay" hidden>
        <form method="GET" action="<?php echo e(route('store.products')); ?>" class="search-overlay-form" data-search-form data-suggest-url="<?php echo e(route('store.search.suggestions')); ?>">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--muted)" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" name="q" placeholder="Search products, recipes, articles..." autocomplete="off" class="search-overlay-input" data-search-input value="<?php echo e(request('q')); ?>">
            <button type="button" class="search-overlay-close" data-search-close aria-label="Close search">&times;</button>
            <div class="search-suggest-box hidden" data-search-results></div>
        </form>
    </div>
</header>


<div class="nn-fullscreen-menu" id="nnFullscreenMenu" aria-hidden="true">
    <button type="button" class="nn-menu-close-btn" id="nnMenuCloseBtn" aria-label="Close menu">
        <span></span><span></span>
    </button>
    <div class="nn-fullscreen-menu__inner">

        
        <nav class="nn-menu-main" role="navigation" aria-label="Main menu">
            <a href="<?php echo e(route('store.home')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.home') ? 'active' : ''); ?>">Home</a>
            <a href="<?php echo e(route('store.products')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.products*') ? 'active' : ''); ?>">Shop</a>
            <a href="<?php echo e(route('store.pricing')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.pricing*') ? 'active' : ''); ?>">Subscriptions</a>
            <a href="<?php echo e(route('store.recipes')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.recipes') ? 'active' : ''); ?>">Recipes</a>
            <a href="<?php echo e(route('store.tools.index')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.tools.*') ? 'active' : ''); ?>">Tools</a>
            <a href="<?php echo e(route('store.blog.index')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.blog*') ? 'active' : ''); ?>">Learn</a>
            <a href="<?php echo e(route('store.about')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.about') ? 'active' : ''); ?>">About</a>
            <a href="<?php echo e(route('store.contact')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.contact') ? 'active' : ''); ?>">Contact</a>
            <a href="<?php echo e(route('store.faq')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.faq') ? 'active' : ''); ?>">FAQ</a>
            <a href="<?php echo e(route('store.refer-friends')); ?>" class="nn-menu-link <?php echo e(request()->routeIs('store.refer-friends') ? 'active' : ''); ?>">Refer Friends</a>
        </nav>

        
        <div class="nn-menu-social">
            <a href="https://www.instagram.com/numnameats" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                    <circle cx="12" cy="12" r="4" />
                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                </svg>
            </a>
            <a href="https://www.facebook.com/numnamofficial" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                </svg>
            </a>
            <a href="https://www.linkedin.com/company/numnam" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="nn-social-link">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
                    <circle cx="4" cy="4" r="2" />
                </svg>
            </a>
            <div class="nn-menu-social-auth">
                <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('store.account')); ?>" class="nn-social-auth-link">My Account</a>
                <a href="<?php echo e(route('store.logout')); ?>" onclick="event.preventDefault(); document.getElementById('nn-logout-form').submit();" class="nn-social-auth-link">Log out</a>
                <form id="nn-logout-form" method="POST" action="<?php echo e(route('store.logout')); ?>" style="display:none;"><?php echo csrf_field(); ?></form>
                <?php else: ?>
                <a href="<?php echo e(route('store.login')); ?>" class="nn-social-auth-link">Login</a>
                <a href="<?php echo e(route('store.register')); ?>" class="nn-social-auth-link">Register</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div><?php /**PATH C:\xampp\htdocs\numnam-api\resources\views/store/partials/header.blade.php ENDPATH**/ ?>