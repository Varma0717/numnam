<header class="site-header">
    <div class="site-header-inner">
        <a href="{{ route('store.home') }}" class="brand">
            <span class="brand-dot">NN</span>
            <span>NumNam</span>
        </a>

        <button type="button" class="nav-toggle" data-nav-toggle>Menu</button>

        <nav class="site-nav">
            <a href="{{ route('store.home') }}" class="{{ request()->routeIs('store.home') ? 'active' : '' }}">Home</a>
            <div class="nav-item has-mega">
                <a href="{{ route('store.products') }}" class="{{ request()->routeIs('store.products*') ? 'active' : '' }}">Products</a>
                <div class="mega-menu" role="region" aria-label="Products menu">
                    <div class="mega-grid">
                        @forelse(($megaCategories ?? collect()) as $category)
                            <section class="mega-col">
                                <h4>
                                    <a href="{{ route('store.products', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                                </h4>
                                <ul>
                                    @forelse($category->products as $product)
                                        <li>
                                            <a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
                                        </li>
                                    @empty
                                        <li><span class="meta">Coming soon</span></li>
                                    @endforelse
                                </ul>
                            </section>
                        @empty
                            <section class="mega-col">
                                <h4>Catalog</h4>
                                <p class="meta">Products will appear here once categories are published.</p>
                            </section>
                        @endforelse
                    </div>
                </div>
            </div>
            <a href="{{ route('store.pricing') }}" class="{{ request()->routeIs('store.pricing*') ? 'active' : '' }}">Subscriptions</a>
            <a href="{{ route('store.about') }}" class="{{ request()->routeIs('store.about') ? 'active' : '' }}">About Us</a>
            <div class="nav-item has-mega">
                <a href="{{ route('store.blog.index') }}" class="{{ request()->routeIs('store.blog*') || request()->routeIs('store.recipes') || request()->routeIs('store.faq') ? 'active' : '' }}">Parents Corner</a>
                <div class="mega-menu parents-mega" role="region" aria-label="Parents Corner menu">
                    <div class="mega-grid">
                        <section class="mega-col">
                            <h4><a href="{{ route('store.blog.index') }}">Blog</a></h4>
                            <p class="meta">Articles on feeding, nutrition, and routines.</p>
                        </section>
                        <section class="mega-col">
                            <h4><a href="{{ route('store.recipes') }}">Recipes</a></h4>
                            <p class="meta">Simple meal ideas and stage-based recipes.</p>
                        </section>
                        <section class="mega-col">
                            <h4><a href="{{ route('store.faq') }}">FAQ</a></h4>
                            <p class="meta">Quick answers about products, delivery, and payment.</p>
                        </section>
                    </div>
                </div>
            </div>
            <a href="{{ route('store.contact') }}" class="{{ request()->routeIs('store.contact') ? 'active' : '' }}">Contact</a>
            <a href="{{ route('store.refer-friends') }}" class="{{ request()->routeIs('store.refer-friends') ? 'active' : '' }}">Refer Friends</a>
            <a href="{{ route('store.cart') }}" class="{{ request()->routeIs('store.cart') ? 'active' : '' }}">Cart</a>
        </nav>

        <div class="site-actions">
            @auth
                <a class="btn-ghost" href="{{ route('store.account') }}">My Account</a>
                <form method="POST" action="{{ route('store.logout') }}">
                    @csrf
                    <button type="submit" class="cta-btn">Logout</button>
                </form>
            @else
                <a class="btn-ghost" href="{{ route('store.login') }}">Login</a>
                <a class="cta-btn" href="{{ route('store.register') }}">Register</a>
            @endauth
            <a class="btn-soft" href="{{ url('/admin') }}">Admin</a>
        </div>
    </div>

    <nav class="mobile-nav" data-mobile-nav>
        <a href="{{ route('store.home') }}">Home</a>
        <a href="{{ route('store.products') }}">Products</a>
        @foreach(($megaCategories ?? collect())->take(5) as $category)
            <a href="{{ route('store.products', ['category' => $category->slug]) }}">- {{ $category->name }}</a>
        @endforeach
        <a href="{{ route('store.pricing') }}">Subscriptions</a>
        <a href="{{ route('store.about') }}">About Us</a>
        <a href="{{ route('store.blog.index') }}">Blog</a>
        <a href="{{ route('store.recipes') }}">Recipes</a>
        <a href="{{ route('store.faq') }}">FAQ</a>
        <a href="{{ route('store.contact') }}">Contact</a>
        <a href="{{ route('store.refer-friends') }}">Refer Friends</a>
        <a href="{{ route('store.cart') }}">Cart</a>
    </nav>
</header>
