{{-- Newsletter section (above footer) --}}
<div class="footer-newsletter">
    <div class="footer-newsletter-inner">
        <div class="newsletter-copy">
            <h3>Join the NumNam Family</h3>
            <p>Get parenting tips, new product alerts, and exclusive offers delivered to your inbox.</p>
        </div>
        <form class="newsletter-form" method="POST" action="{{ route('store.contact.submit') }}">
            @csrf
            <input type="hidden" name="query_type" value="newsletter">
            <input type="hidden" name="first_name" value="Subscriber">
            <input type="email" name="email" placeholder="Your email address" required class="newsletter-input form-control">
            <button type="submit" class="cta-btn btn btn-primary">Subscribe</button>
        </form>
    </div>
</div>

<footer class="site-footer">
    <div class="site-footer-inner">
        <div class="footer-col footer-brand-col">
            <div class="footer-logo">
                <span class="brand-dot">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.9C15.5 4.9 17 3.5 19 2c1 2 2 4.5 2 8 0 5.5-3.5 10-10 10Z" />
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" />
                    </svg>
                </span>
                <strong>NumNam</strong>
            </div>
            <p class="meta">Doctor-founded baby nutrition with clean ingredients, subscriptions, and parent education content.</p>
            <div class="footer-contact-info">
                <p class="meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg> info@numnam.com</p>
                <p class="meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                    </svg> +91-9014252278</p>
            </div>
            <div class="box-socials-footer">
                <a href="https://www.instagram.com/numnam_baby" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                        <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                    </svg>
                </a>
                <a href="https://www.facebook.com/numnam" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                    </svg>
                </a>
                <a href="https://twitter.com/numnam_baby" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                    </svg>
                </a>
                <a href="https://www.youtube.com/@numnam" target="_blank" rel="noopener noreferrer" aria-label="YouTube">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.43z" />
                        <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" />
                    </svg>
                </a>
            </div>
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
            <a href="{{ route('store.contact') }}">Contact Us</a>
            @auth
            <a href="{{ route('store.account') }}">My Account</a>
            @else
            <a href="{{ route('store.login') }}">My Account</a>
            @endauth
            <a href="{{ route('store.faq') }}">Help Center</a>
        </div>
    </div>

    {{-- Payment & Trust badges --}}
    <div class="footer-bottom">
        <div class="footer-payment-methods">
            <span class="footer-label">We accept:</span>
            <div class="payment-icons">
                <span class="payment-badge">Visa</span>
                <span class="payment-badge">Mastercard</span>
                <span class="payment-badge">UPI</span>
                <span class="payment-badge">Razorpay</span>
                <span class="payment-badge">Stripe</span>
                <span class="payment-badge">Net Banking</span>
                <span class="payment-badge">COD</span>
            </div>
        </div>
        <p>&copy; {{ date('Y') }} NumNam. All rights reserved. | Doctor-founded baby nutrition.</p>
    </div>
</footer>