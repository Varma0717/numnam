@extends('store.layouts.app')
@section('title', 'My Wishlist — NumNam')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fffaf4] via-white to-[#fff3e6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-orange-100/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">My Wishlist</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Saved Products</h1>
            <p class="mt-3 text-base leading-relaxed text-slate-600">Items you've saved for later.</p>
        </div>
    </div>
</section>

@php
$productPlaceholders = [
asset('assets/images/Puffs/Tikka%20Puffies/front.jpg'),
asset('assets/images/Puffs/Tomaty%20Pumpos/front.jpg'),
asset('assets/images/Purees/mangy%20chewy%201.png'),
asset('assets/images/Purees/brocco%20pop%201.png'),
];
@endphp

<section class="section pb-14">
    @if($products->count())
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @foreach($products as $product)
        @if($product)
        @php($placeholder = $productPlaceholders[$product->id % count($productPlaceholders)])
        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <a class="relative block aspect-[4/3] overflow-hidden" href="{{ route('store.product.show', $product) }}">
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105" src="{{ $placeholder }}" alt="{{ $product->name }}" loading="lazy">
                @if($product->sale_price)
                <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-2.5 py-0.5 text-xs font-bold text-white">
                    -{{ round((1 - $product->sale_price / $product->price) * 100) }}%
                </span>
                @endif
            </a>
            <div class="px-5 pb-5 pt-4">
                <h4 class="font-semibold text-slate-900">
                    <a class="hover:text-numnam-700" href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
                </h4>
                <div class="mt-1 flex items-baseline gap-2">
                    <strong class="text-base font-bold text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                    @if($product->sale_price)
                    <del class="text-sm text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                    @endif
                </div>
                <div class="mt-3 flex gap-2">
                    <form method="POST" action="{{ route('store.cart.add', $product) }}" class="flex-1">
                        @csrf
                        <button class="h-10 w-full rounded-full bg-numnam-600 text-sm font-semibold text-white transition hover:bg-numnam-700" type="submit">Add to Cart</button>
                    </form>
                    <form method="POST" action="{{ route('store.wishlist.toggle', $product) }}">
                        @csrf
                        <button type="submit" class="flex h-10 w-10 items-center justify-center rounded-full border border-rose-200 bg-rose-50 text-rose-500 transition hover:bg-rose-100" aria-label="Remove from wishlist">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </article>
        @endif
        @endforeach
    </div>
    <div class="mt-8">{{ $products->links() }}</div>
    @else
    <div class="rounded-3xl border border-slate-200 bg-white px-8 py-16 text-center shadow-sm">
        <svg class="mx-auto mb-4 h-14 w-14 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
        </svg>
        <h3 class="text-lg font-bold text-slate-900">Your wishlist is empty</h3>
        <p class="mt-2 text-sm text-slate-500">Browse products and tap the heart to save them here.</p>
        <a class="mt-5 inline-flex h-11 items-center rounded-full bg-numnam-600 px-8 text-sm font-semibold text-white transition hover:bg-numnam-700" href="{{ route('store.products') }}">Browse Products</a>
    </div>
    @endif
</section>
@endsection