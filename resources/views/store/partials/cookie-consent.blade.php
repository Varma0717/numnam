@if(!request()->cookie('numnam_cookie_consent_v3'))
<div class="cookie-consent" id="cookieConsent">
    <div class="cookie-consent-inner">
        <div class="cookie-consent-text">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                <line x1="9" y1="9" x2="9.01" y2="9" />
                <line x1="15" y1="9" x2="15.01" y2="9" />
            </svg>
            <p>We use cookies to enhance your experience. By continuing to browse, you agree to our <a href="{{ route('store.legal.cookie') }}">Cookie Policy</a>.</p>
        </div>
        <div class="cookie-consent-actions">
            <button type="button" class="btn-primary" onclick="acceptCookies()">Accept All</button>
            <button type="button" class="btn btn-secondary" onclick="dismissCookies()">Decline</button>
        </div>
    </div>
</div>
<script>
    (function() {
        var body = document.body;
        var el = document.getElementById('cookieConsent');
        if (!el) return;
        if (document.cookie.split(';').some(function(c) {
                return c.trim().indexOf('numnam_cookie_consent_v3=') === 0;
            })) {
            if (body) body.classList.remove('cookie-banner-visible');
            el.remove();
        } else {
            if (body) body.classList.add('cookie-banner-visible');
        }
    })();

    function acceptCookies() {
        document.cookie = 'numnam_cookie_consent_v3=1;path=/;max-age=31536000;SameSite=Lax';
        document.body.classList.remove('cookie-banner-visible');
        var el = document.getElementById('cookieConsent');
        if (el) el.remove();
    }

    function dismissCookies() {
        document.cookie = 'numnam_cookie_consent_v3=dismissed;path=/;max-age=2592000;SameSite=Lax';
        document.body.classList.remove('cookie-banner-visible');
        var el = document.getElementById('cookieConsent');
        if (el) el.remove();
    }
</script>
@endif