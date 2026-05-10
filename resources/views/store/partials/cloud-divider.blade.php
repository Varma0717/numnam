@props(['color' => '#FFFDF8', 'flip' => false])
<div class="cloud-divider{{ $flip ? ' cloud-divider--flip' : '' }}" aria-hidden="true">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path fill="{{ $color }}" d="M0,40 C120,80 240,0 360,40 C480,80 600,0 720,40 C840,80 960,0 1080,40 C1200,80 1320,0 1440,40 L1440,80 L0,80 Z" />
    </svg>
</div>