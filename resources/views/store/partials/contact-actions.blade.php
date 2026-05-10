@php
$wishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
@endphp

<div class="mobile-app-navbar fixed inset-x-3 bottom-3 z-40 lg:hidden">
    <div class="mx-auto w-full max-w-sm rounded-[1.35rem] border border-slate-200/80 bg-white/95 p-2 shadow-[0_14px_34px_rgba(15,23,42,0.16)] backdrop-blur">
        <div class="grid grid-cols-4 gap-2">
            <a href="{{ route('store.products') }}" class="mobile-app-item {{ request()->routeIs('store.products*') || request()->routeIs('store.category*') ? 'is-active' : '' }}" aria-label="Shop">
                <span class="mobile-app-icon" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9.5h18"></path>
                        <path d="M7 9.5 8 5h8l1 4.5"></path>
                        <rect x="4" y="9.5" width="16" height="10" rx="2"></rect>
                    </svg>
                </span>
            </a>

            <a href="{{ auth()->check() ? route('store.wishlist') : route('store.login') }}" class="mobile-app-item {{ request()->routeIs('store.wishlist*') ? 'is-active' : '' }}" aria-label="Wishlist">
                <span class="mobile-app-icon" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8z"></path>
                    </svg>
                    @if($wishlistCount > 0)
                    <span class="mobile-app-count">{{ $wishlistCount }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ route('store.cart') }}" class="mobile-app-item {{ request()->routeIs('store.cart*') ? 'is-active' : '' }}" aria-label="Cart">
                <span class="mobile-app-icon" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="20" r="1"></circle>
                        <circle cx="18" cy="20" r="1"></circle>
                        <path d="M3 4h2l2.1 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 7H7"></path>
                    </svg>
                    @if(($cartItemCount ?? 0) > 0)
                    <span class="mobile-app-count">{{ $cartItemCount }}</span>
                    @endif
                </span>
            </a>

            <a href="{{ auth()->check() ? route('store.account') : route('store.login') }}" class="mobile-app-item {{ request()->routeIs('store.account') || request()->routeIs('store.login*') || request()->routeIs('store.register*') ? 'is-active' : '' }}" aria-label="Account">
                <span class="mobile-app-icon" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M5 20a7 7 0 0 1 14 0"></path>
                    </svg>
                </span>
            </a>
        </div>
    </div>
</div>