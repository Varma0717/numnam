@if(!request()->cookie('numnam_cookie_consent'))
<div class="cookie-consent" id="cookieConsent">
    <div class="cookie-consent-inner">
        <div class="cookie-consent-text">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            <p>We use cookies to enhance your experience. By continuing to browse, you agree to our <a href="{{ route('store.legal.cookie') }}">Cookie Policy</a>.</p>
        </div>
        <div class="cookie-consent-actions">
            <button type="button" class="btn-primary" onclick="acceptCookies()">Accept All</button>
            <button type="button" class="btn-ghost" onclick="this.closest('.cookie-consent').remove()">Decline</button>
        </div>
    </div>
</div>
<script>
function acceptCookies(){document.cookie='numnam_cookie_consent=1;path=/;max-age=31536000;SameSite=Lax';document.getElementById('cookieConsent').remove();}
</script>
@endif
