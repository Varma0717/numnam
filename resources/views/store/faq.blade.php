@extends('store.layouts.app')

@section('title', 'NumNam - FAQ')
@section('meta_description', 'Find answers to common questions about NumNam baby food, subscriptions, delivery, payments and more.')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">FAQ</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Quick answers for parents</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Everything from age suitability to subscriptions, payment, and delivery.</p>
        </div>
    </div>
</section>

<section class="section pb-10">
    <div class="mx-auto max-w-3xl space-y-3">
        @foreach($faqs as $i => $faq)
        <div class="accordion-item{{ $i === 0 ? ' open' : '' }} overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <button type="button" class="accordion-trigger flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-slate-900 transition hover:bg-slate-50" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                <span>{{ $faq['q'] }}</span>
                <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>
            <div class="accordion-panel" @if($i===0) style="max-height:200px" @endif>
                <div class="accordion-panel-inner border-t border-slate-100 px-5 py-4 text-sm leading-relaxed text-slate-600">{{ $faq['a'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>

<section class="section pb-12">
    <div class="rounded-3xl border border-numnam-100 bg-gradient-to-br from-numnam-50 to-white px-8 py-10 text-center shadow-sm">
        <h3 class="text-xl font-extrabold text-slate-900">Still have questions?</h3>
        <p class="mt-2 text-sm text-slate-500">Our care team is here to help you with anything.</p>
        <a class="mt-5 inline-flex h-11 items-center rounded-full bg-numnam-600 px-8 text-sm font-semibold text-white transition hover:bg-numnam-700" href="{{ route('store.contact') }}">Contact Us</a>
    </div>
</section>
@endsection