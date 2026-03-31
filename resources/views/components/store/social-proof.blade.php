@props([
'customers' => '10,000+',
'rating' => '4.8',
'compact' => false,
])

<div @class([ 'social-proof-strip' , 'social-proof-strip-compact'=> $compact,
    ])>
    <span class="social-proof-item">
        <svg class="social-proof-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10 2a3 3 0 110 6 3 3 0 010-6zM4.5 15.5A4.5 4.5 0 019 11h2a4.5 4.5 0 014.5 4.5V17h-11v-1.5z" />
        </svg>
        <span>Trusted by {{ $customers }} customers</span>
    </span>
    <span class="social-proof-item">
        <span class="social-proof-stars" aria-hidden="true">★★★★★</span>
        <span>Rated {{ $rating }} stars</span>
    </span>
</div>