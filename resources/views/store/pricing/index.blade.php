@extends('store.layouts.app')

@section('title', 'NumNam - Subscriptions')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Subscription Plans</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Choose the right plan for your family</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Save more with regular deliveries. Every plan includes free shipping and the flexibility to pause or cancel anytime.</p>
        </div>
    </div>
</section>

<section class="section pb-10">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($plans as $plan)
        @php($isBestValue = collect($plan->features ?? [])->contains(fn($feature) => strtolower((string) $feature) === 'best value'))
        @php($cycleLabel = $plan->billing_cycle === 'one_time' ? 'One time' : 'Every ' . strtolower(str_replace('_', ' ', $plan->billing_cycle)))
        <article class="relative overflow-visible rounded-3xl border bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md {{ $isBestValue ? 'border-numnam-300 ring-2 ring-numnam-200' : 'border-slate-200' }}">
            @if($isBestValue)
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full border border-numnam-300 bg-numnam-600 px-4 py-1 text-xs font-bold text-white shadow-sm">Best Value</span>
            @endif
            <div class="px-6 py-7">
                <h4 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h4>
                <p class="mt-1 text-sm text-slate-500">{{ $plan->description }}</p>
                <div class="mt-4">
                    <strong class="text-3xl font-extrabold text-slate-900">Rs {{ number_format($plan->price, 0) }}</strong>
                </div>
                <p class="mt-1 text-xs text-slate-400">{{ $cycleLabel }}</p>
                @if($plan->duration)
                <p class="text-xs text-slate-400">{{ $plan->duration }}</p>
                @endif
                <div class="mt-4 flex flex-wrap gap-1.5">
                    @foreach(($plan->features ?? []) as $feature)
                    <span class="rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-medium text-slate-600">{{ $feature }}</span>
                    @endforeach
                </div>
                <div class="mt-5">
                    @auth
                    <form method="POST" action="{{ route('store.pricing.subscribe', $plan) }}">
                        @csrf
                        <button class="h-11 w-full rounded-full {{ $isBestValue ? 'bg-numnam-600 text-white hover:bg-numnam-700' : 'border border-numnam-300 bg-white text-numnam-700 hover:bg-numnam-50' }} text-sm font-semibold transition" type="submit">Select Plan</button>
                    </form>
                    @endauth
                    @guest
                    <a class="flex h-11 items-center justify-center rounded-full border border-numnam-300 bg-white text-sm font-semibold text-numnam-700 transition hover:bg-numnam-50" href="{{ route('store.login') }}">Login to Subscribe</a>
                    @endguest
                </div>
            </div>
        </article>
        @empty
        <p class="col-span-full text-center text-sm text-slate-500">No active plans yet.</p>
        @endforelse
    </div>
</section>

<section class="section pb-14">
    <div class="mx-auto max-w-3xl">
        <h2 class="mb-5 text-xl font-extrabold text-slate-900">Frequently Asked Questions</h2>
        <div class="space-y-3">
            <div class="accordion-item overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <button class="accordion-trigger flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">
                    Can I cancel anytime?
                    <svg class="h-5 w-5 shrink-0 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="accordion-panel">
                    <p class="border-t border-slate-100 px-5 py-4 text-sm text-slate-600">Yes! You can pause or cancel your subscription from your account dashboard at any time.</p>
                </div>
            </div>
            <div class="accordion-item overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <button class="accordion-trigger flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">
                    When will my order be delivered?
                    <svg class="h-5 w-5 shrink-0 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="accordion-panel">
                    <p class="border-t border-slate-100 px-5 py-4 text-sm text-slate-600">Subscription orders are dispatched at the start of each cycle. Delivery takes 3-5 business days.</p>
                </div>
            </div>
            <div class="accordion-item overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <button class="accordion-trigger flex w-full items-center justify-between px-5 py-4 text-left text-sm font-semibold text-slate-900 hover:bg-slate-50">
                    Can I switch plans?
                    <svg class="h-5 w-5 shrink-0 text-slate-400" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="accordion-panel">
                    <p class="border-t border-slate-100 px-5 py-4 text-sm text-slate-600">Absolutely. You can upgrade or downgrade at any time and the change will take effect on your next billing cycle.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection