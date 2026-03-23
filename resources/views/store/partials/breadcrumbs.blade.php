@php
    $crumbs = [['label' => 'Home', 'url' => route('store.home')]];

    if (request()->routeIs('store.products') || request()->routeIs('store.product.show')) {
        $crumbs[] = ['label' => 'Products', 'url' => route('store.products')];
    }
    if (request()->routeIs('store.product.show') && isset($product)) {
        $crumbs[] = ['label' => $product->name, 'url' => null];
    }
    if (request()->routeIs('store.pricing*')) {
        $crumbs[] = ['label' => 'Subscriptions', 'url' => null];
    }
    if (request()->routeIs('store.blog.index')) {
        $crumbs[] = ['label' => 'Blog', 'url' => null];
    }
    if (request()->routeIs('store.blog.show') && isset($blog)) {
        $crumbs[] = ['label' => 'Blog', 'url' => route('store.blog.index')];
        $crumbs[] = ['label' => Str::limit($blog->title, 40), 'url' => null];
    }
    if (request()->routeIs('store.cart')) {
        $crumbs[] = ['label' => 'Cart', 'url' => null];
    }
    if (request()->routeIs('store.checkout')) {
        $crumbs[] = ['label' => 'Cart', 'url' => route('store.cart')];
        $crumbs[] = ['label' => 'Checkout', 'url' => null];
    }
    if (request()->routeIs('store.about')) {
        $crumbs[] = ['label' => 'About', 'url' => null];
    }
    if (request()->routeIs('store.contact')) {
        $crumbs[] = ['label' => 'Contact', 'url' => null];
    }
    if (request()->routeIs('store.faq')) {
        $crumbs[] = ['label' => 'FAQ', 'url' => null];
    }
    if (request()->routeIs('store.recipes')) {
        $crumbs[] = ['label' => 'Recipes', 'url' => null];
    }
    if (request()->routeIs('store.refer-friends')) {
        $crumbs[] = ['label' => 'Refer Friends', 'url' => null];
    }
    if (request()->routeIs('store.account')) {
        $crumbs[] = ['label' => 'My Account', 'url' => null];
    }
    if (request()->routeIs('store.login')) {
        $crumbs[] = ['label' => 'Login', 'url' => null];
    }
    if (request()->routeIs('store.register')) {
        $crumbs[] = ['label' => 'Register', 'url' => null];
    }
    if (request()->routeIs('store.legal*')) {
        $crumbs[] = ['label' => $page['title'] ?? 'Policy', 'url' => null];
    }
    if (request()->routeIs('store.order.success')) {
        $crumbs[] = ['label' => 'Order Confirmed', 'url' => null];
    }
@endphp

@if(count($crumbs) > 1)
<nav class="breadcrumbs" aria-label="Breadcrumb">
    <ol itemscope itemtype="https://schema.org/BreadcrumbList">
        @foreach($crumbs as $i => $crumb)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if($crumb['url'] && $i < count($crumbs) - 1)
                    <a itemprop="item" href="{{ $crumb['url'] }}"><span itemprop="name">{{ $crumb['label'] }}</span></a>
                @else
                    <span itemprop="name" aria-current="page">{{ $crumb['label'] }}</span>
                @endif
                <meta itemprop="position" content="{{ $i + 1 }}">
            </li>
        @endforeach
    </ol>
</nav>
@endif
