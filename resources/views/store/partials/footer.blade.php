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
                <img src="{{ asset('assets/images/Logo/TM.png') }}" alt="NumNam logo" width="36" height="36" loading="lazy" class="brand-logo-img">
            </div>
            <p class="meta">Doctor-founded baby nutrition with clean ingredients, subscriptions, and parent education content.</p>
            <div class="footer-contact-info">
                <p class="meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                    <a href="mailto:info@numnam.com" class="hover:text-white transition-colors duration-200">info@numnam.com</a>
                </p>
                <p class="meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                    </svg>
                    <a href="tel:+919014252278" class="hover:text-white transition-colors duration-200">+91 90142 52278</a>
                </p>
                <p class="meta"><svg width="14" height="14" viewBox="0 0 24 24" fill="#25D366">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    <a href="https://wa.me/919014252278" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">WhatsApp Support</a>
                </p>
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
                {{-- Visa --}}
                <span class="payment-badge" title="Visa">
                    <svg width="38" height="14" viewBox="0 0 80 26" xmlns="http://www.w3.org/2000/svg">
                        <text x="0" y="22" font-family="Arial,sans-serif" font-size="26" font-weight="800" fill="#1a1f71" letter-spacing="-1">VISA</text>
                    </svg>
                </span>
                {{-- Mastercard --}}
                <span class="payment-badge" title="Mastercard" style="padding:0 5px;">
                    <svg width="32" height="20" viewBox="0 0 50 32" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="16" r="12" fill="#EB001B" />
                        <circle cx="32" cy="16" r="12" fill="#F79E1B" />
                        <path d="M25 7.2a12 12 0 010 17.6A12 12 0 0125 7.2z" fill="#FF5F00" />
                    </svg>
                </span>
                {{-- UPI --}}
                <span class="payment-badge" title="UPI" style="display:inline-flex;align-items:center;gap:3px;">
                    <svg width="26" height="16" viewBox="0 0 60 32" xmlns="http://www.w3.org/2000/svg">
                        <text x="0" y="24" font-family="Arial,sans-serif" font-size="22" font-weight="800" fill="#6c4298" letter-spacing="0.5">UP</text>
                        <text x="36" y="24" font-family="Arial,sans-serif" font-size="22" font-weight="800" fill="#097939">I</text>
                        <path d="M48 6l8 10-8 10" stroke="#097939" stroke-width="2.5" stroke-linecap="round" fill="none" />
                    </svg>
                </span>
                {{-- Razorpay --}}
                <span class="payment-badge" title="Razorpay" style="display:inline-flex;align-items:center;gap:3px;">
                    <svg width="14" height="18" viewBox="0 0 28 36" xmlns="http://www.w3.org/2000/svg">
                        <polygon points="6,32 18,4 24,4 18,18 28,4 22,32 16,32 20,18 14,32" fill="#3395FF" />
                    </svg>
                    <span style="font-size:10px;font-weight:700;color:#3395FF;letter-spacing:-0.2px;">Razorpay</span>
                </span>
                {{-- Stripe --}}
                <span class="payment-badge" title="Stripe">
                    <svg width="36" height="16" viewBox="0 0 80 28" xmlns="http://www.w3.org/2000/svg">
                        <text x="0" y="22" font-family="Arial,sans-serif" font-size="22" font-weight="700" fill="#635bff" letter-spacing="-0.5">stripe</text>
                    </svg>
                </span>
                {{-- Net Banking --}}
                <span class="payment-badge" title="Net Banking" style="display:inline-flex;align-items:center;gap:4px;">
                    <svg width="18" height="16" viewBox="0 0 36 28" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="8" width="32" height="18" rx="2" stroke="#94a3b8" stroke-width="2" fill="none" />
                        <path d="M2 14h32" stroke="#94a3b8" stroke-width="2" />
                        <rect x="6" y="18" width="10" height="3" rx="1.5" fill="#94a3b8" />
                        <polygon points="18,0 2,8 34,8" fill="#94a3b8" />
                    </svg>
                    <span style="font-size:9px;font-weight:700;color:#94a3b8;white-space:nowrap;">Net Banking</span>
                </span>
                {{-- COD --}}
                <span class="payment-badge" title="Cash on Delivery" style="display:inline-flex;align-items:center;gap:3px;">
                    <svg width="18" height="18" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="18" cy="18" r="15" stroke="#86efac" stroke-width="2" fill="none" />
                        <text x="10" y="24" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="#86efac">₹</text>
                    </svg>
                    <span style="font-size:9px;font-weight:700;color:#86efac;">COD</span>
                </span>
            </div>
        </div>
        <p>&copy; {{ date('Y') }} NumNam. All rights reserved. | Doctor-founded baby nutrition.</p>
    </div>
</footer>