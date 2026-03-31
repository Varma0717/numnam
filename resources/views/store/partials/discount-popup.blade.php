<div id="discountPopup" class="fixed inset-0 z-[90] hidden" role="dialog" aria-modal="true" aria-labelledby="discountPopupTitle">
    <button type="button" class="absolute inset-0 bg-slate-900/50 backdrop-blur-[1px]" data-discount-close aria-label="Dismiss discount popup"></button>

    <div class="relative mx-auto mt-20 w-[min(92vw,28rem)] rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:mt-28 sm:p-7">
        <button type="button" class="absolute right-3 top-3 inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition-colors duration-200 hover:bg-slate-100 hover:text-slate-700" data-discount-close aria-label="Close popup">&times;</button>

        <p class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-emerald-700">Welcome Offer</p>
        <h2 id="discountPopupTitle" class="mt-4 text-2xl font-bold text-slate-900">Get 10% OFF Your First Order</h2>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">Use this code at checkout and enjoy a clean-label start with NumNam.</p>

        <div class="mt-5 flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
            <span id="discountCode" class="text-base font-bold tracking-[0.12em] text-slate-900">WELCOME10</span>
            <button type="button" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900" data-copy-discount>Copy Code</button>
        </div>

        <div class="mt-5 flex gap-2">
            <a href="{{ route('store.products') }}" class="hero-cta w-full text-center">Shop Now</a>
            <button type="button" class="inline-flex w-full items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-all duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:text-slate-900" data-discount-close>Maybe Later</button>
        </div>
    </div>
</div>