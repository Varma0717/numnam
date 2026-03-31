@php
$supportPhoneDisplay = '+91 90142 52278';
$supportPhoneHref = '+919014252278';
$supportEmail = 'info@numnam.com';
$whatsAppNumber = '919014252278';
$whatsAppHref = 'https://wa.me/' . $whatsAppNumber;
@endphp

<div class="pointer-events-none fixed bottom-3 left-1/2 z-40 w-[calc(100%-1.25rem)] max-w-xl -translate-x-1/2">
    <div class="pointer-events-auto rounded-full border border-slate-200/90 bg-white/95 p-1.5 shadow-lg backdrop-blur">
        <div class="grid grid-cols-3 gap-1">
            <a href="tel:{{ $supportPhoneHref }}" class="inline-flex items-center justify-center gap-1.5 rounded-full px-2.5 py-2 text-xs font-medium text-slate-700 transition-colors duration-200 hover:bg-slate-100 sm:text-sm" aria-label="Call {{ $supportPhoneDisplay }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                </svg>
                <span class="hidden sm:inline">Call</span>
            </a>

            <a href="{{ $whatsAppHref }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-2 text-xs font-semibold text-emerald-700 transition-colors duration-200 hover:bg-emerald-100 sm:text-sm" aria-label="Chat on WhatsApp">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 11.5A8.5 8.5 0 005.3 6.7 8.4 8.4 0 003.5 16.4L2 22l5.8-1.5A8.5 8.5 0 1021 11.5z" />
                    <path d="M8.5 9.5c.2 1 1.1 2.4 2.1 3.4s2.4 1.9 3.4 2.1c.5.1 1-.1 1.3-.5l.8-1" />
                </svg>
                <span class="hidden sm:inline">WhatsApp</span>
            </a>

            <a href="mailto:{{ $supportEmail }}" class="inline-flex items-center justify-center gap-1.5 rounded-full px-2.5 py-2 text-xs font-medium text-slate-700 transition-colors duration-200 hover:bg-slate-100 sm:text-sm" aria-label="Email {{ $supportEmail }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <path d="M22 7l-10 7L2 7" />
                </svg>
                <span class="hidden sm:inline">Email</span>
            </a>
        </div>
    </div>
</div>

<a href="{{ $whatsAppHref }}" target="_blank" rel="noopener noreferrer" class="fixed bottom-20 right-4 z-50 inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500 text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:bg-emerald-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300" aria-label="Open WhatsApp chat">
    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 11.5A8.5 8.5 0 005.3 6.7 8.4 8.4 0 003.5 16.4L2 22l5.8-1.5A8.5 8.5 0 1021 11.5z" />
        <path d="M8.5 9.5c.2 1 1.1 2.4 2.1 3.4s2.4 1.9 3.4 2.1c.5.1 1-.1 1.3-.5l.8-1" />
    </svg>
</a>