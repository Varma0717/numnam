<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="footer-col">
            <strong>NumNam Storefront</strong>
            <p class="meta">Doctor-founded baby nutrition platform with clean ingredients, subscriptions, and parent education content.</p>
            <p class="meta">Email: info@numnam.com</p>
            <p class="meta">Phone: +91-9014252278</p>
        </div>
        <div class="footer-col">
            <strong>Shop</strong>
            <a href="{{ route('store.products') }}">Products</a>
            <a href="{{ route('store.pricing') }}">Subscriptions</a>
            <a href="{{ route('store.refer-friends') }}">Refer Friends</a>
            <a href="{{ route('store.cart') }}">Cart</a>
            @auth
                <a href="{{ route('store.checkout') }}">Checkout</a>
            @else
                <a href="{{ route('store.login') }}">Checkout</a>
            @endauth
        </div>
        <div class="footer-col">
            <strong>Parents Corner</strong>
            <a href="{{ route('store.blog.index') }}">Blog</a>
            <a href="{{ route('store.recipes') }}">Recipes</a>
            <a href="{{ route('store.faq') }}">FAQ</a>
            <a href="{{ route('store.about') }}">About Us</a>
        </div>
        <div class="footer-col">
            <strong>Policies</strong>
            <a href="{{ route('store.legal.terms') }}">Terms & Conditions</a>
            <a href="{{ route('store.legal.privacy') }}">Privacy Policy</a>
            <a href="{{ route('store.legal.shipping') }}">Shipping Policy</a>
            <a href="{{ route('store.legal.refund') }}">Refund Policy</a>
            <a href="{{ route('store.legal.cookie') }}">Cookie Policy</a>
            <a href="{{ route('store.legal.payment-methods') }}">Payment Methods</a>
        </div>
        <div class="footer-col">
            <strong>Support</strong>
            <a href="{{ route('store.contact') }}">Contact</a>
            <a href="{{ route('store.account') }}">My Account</a>
            <a href="{{ url('/admin') }}">Admin</a>
        </div>
    </div>
</footer>
