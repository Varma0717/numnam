@extends('store.layouts.app')

@section('title', 'NumNam - Cart')

@section('content')
@php
$productPlaceholders = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Purees/appi%20pooch%201.png'),
asset('assets/images/Purees/berry%20swush%201.png'),
];
@endphp

<section class="hero section in-view">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fff5f8] via-white to-[#eef9f6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-pastel-mint/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Cart</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Your selected products</h1>
            <p class="mt-3 text-base leading-relaxed text-slate-600">Update quantity or proceed to checkout.</p>
        </div>
    </div>
</section>

<section class="section">
    @if(empty($items))
    <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" class="mx-auto mb-5 text-slate-300">
            <circle cx="9" cy="21" r="1" />
            <circle cx="20" cy="21" r="1" />
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
        </svg>
        <h3 class="text-2xl font-semibold text-slate-900">Your cart is empty</h3>
        <p class="mx-auto mb-4 mt-2 max-w-xl text-sm text-slate-600">Looks like you have not added anything yet.</p>
        <a class="inline-flex items-center justify-center rounded-full bg-numnam-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" href="{{ route('store.products') }}">Browse Products</a>
    </div>
    @else
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <div class="rounded-3xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
            @foreach($items as $item)
            @php
            $placeholderImage = $productPlaceholders[$item['product']->id % count($productPlaceholders)];
            $lineImage = $item['product']->image ?: $placeholderImage;
            @endphp
            <article class="flex flex-col gap-4 border-b border-slate-200 px-2 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4">
                    <div class="h-20 w-20 shrink-0 rounded-2xl bg-slate-100" style="background-image:url('{{ $lineImage }}'); background-size:cover; background-position:center;"></div>
                    <div>
                        <h4 class="text-base font-semibold text-slate-900"><a href="{{ route('store.product.show', $item['product']) }}">{{ $item['product']->name }}</a></h4>
                        <p class="mt-1 text-sm text-slate-600">Rs {{ number_format($item['unit_price'], 0) }} each</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2.5">
                            <form method="POST" action="{{ route('store.cart.update', $item['product']) }}" class="qty-form">
                                @csrf
                                <div class="inline-flex h-10 items-center rounded-full border border-slate-200 bg-white px-1.5">
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-lg text-slate-700 transition-colors duration-200 hover:bg-slate-100" onclick="this.nextElementSibling.stepDown();this.form.submit()" aria-label="Decrease quantity">&minus;</button>
                                    <input class="h-8 w-10 border-0 bg-transparent text-center text-sm font-semibold text-slate-800 outline-none" type="number" min="1" name="qty" value="{{ $item['qty'] }}" onchange="this.form.submit()">
                                    <button type="button" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-lg text-slate-700 transition-colors duration-200 hover:bg-slate-100" onclick="this.previousElementSibling.stepUp();this.form.submit()" aria-label="Increase quantity">&plus;</button>
                                </div>
                            </form>
                            <form method="POST" action="{{ route('store.cart.remove', $item['product']) }}">
                                @csrf
                                @method('DELETE')
                                <button class="inline-flex h-10 items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-4 text-sm font-semibold text-rose-700 transition-colors duration-200 hover:bg-rose-100" type="submit" aria-label="Remove {{ $item['product']->name }}">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="sm:text-right">
                    <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Line total</p>
                    <strong class="text-lg text-slate-900">Rs {{ number_format($item['unit_price'] * $item['qty'], 0) }}</strong>
                </div>
            </article>
            @endforeach
        </div>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
            <h3 class="text-lg font-semibold text-slate-900">Order Summary</h3>
            <div class="mt-4 flex items-center justify-between text-sm text-slate-600"><span>Subtotal ({{ count($items) }} item{{ count($items) > 1 ? 's' : '' }})</span><strong class="text-slate-900">Rs {{ number_format($totals['subtotal'], 0) }}</strong></div>
            <div class="mt-2 flex items-center justify-between text-sm text-slate-600"><span>Shipping</span><strong class="text-slate-900">{{ $totals['shipping_fee'] > 0 ? 'Rs ' . number_format($totals['shipping_fee'], 0) : 'Free' }}</strong></div>
            @if($totals['shipping_fee'] == 0)
            <p class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                Free shipping on this order!
            </p>
            @endif
            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4"><span class="text-base font-semibold text-slate-900">Total</span><strong class="text-xl text-slate-900">Rs {{ number_format($totals['total'], 0) }}</strong></div>
            <a class="mt-4 inline-flex h-11 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" href="{{ route('store.checkout') }}">Proceed to Checkout</a>
            <a href="{{ route('store.products') }}" class="mt-3 inline-flex w-full items-center justify-center text-sm font-medium text-slate-600 transition-colors duration-200 hover:text-slate-900">Continue Shopping &rarr;</a>
        </aside>
    </div>
    @endif
</section>
@endsection
