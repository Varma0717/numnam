@extends('store.layouts.app')

@section('title', 'NumNam - Products')

@section('content')
@php
$productPlaceholders = [
asset('assets/images/Puffs/Cheezy%20Bubbles/front.jpg'),
asset('assets/images/Puffs/Manchurian%20Munchos/front.jpg'),
asset('assets/images/Purees/appi%20pooch%202.png'),
asset('assets/images/Purees/berry%20swush%202.png'),
];
@endphp

<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border-2 border-[#FFD93D] bg-white px-3 py-1 font-heading text-xs font-bold uppercase tracking-widest" style="color:#FF6B8A;">Shop NumNam</span>
            <h1 class="mt-4 font-heading text-3xl font-bold tracking-tight sm:text-4xl" style="color:#2D2D3F;">Wholesome Baby Food</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed" style="color:#5e6478;">Stage-based nutrition made from real ingredients, from smooth purees to playful puffs for every milestone.</p>
        </div>
    </div>
</section>



<section class="section py-6">
    <form method="GET" class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-4">
                <label for="catalog-q" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Search</label>
                <input id="catalog-q" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" type="text" name="q" value="{{ request('q') }}" placeholder="Search products...">
            </div>

            <div class="lg:col-span-3">
                <label for="catalog-category" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Category</label>
                <select id="catalog-category" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" name="category">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <label for="catalog-type" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Type</label>
                <select id="catalog-type" class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400" name="type">
                    <option value="">All types</option>
                    @foreach(['puree', 'puffs', 'cookies'] as $type)
                    <option value="{{ $type }}" @selected(request('type')===$type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <button class="inline-flex h-11 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">Apply Filters</button>
            </div>
        </div>
    </form>
</section>

<section class="section">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <span class="text-sm font-medium text-slate-600">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found</span>
        <select class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 focus:border-numnam-400 sm:w-auto" name="sort" onchange="window.location.href=this.value">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(request('sort', 'newest' )==='newest' )>Newest First</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" @selected(request('sort')==='price_low' )>Price: Low to High</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" @selected(request('sort')==='price_high' )>Price: High to Low</option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_az']) }}" @selected(request('sort')==='name_az' )>Name: A-Z</option>
        </select>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($products as $product)
        <article class="group overflow-hidden rounded-[2rem] border-3 bg-white transition-transform duration-200 hover:-translate-y-1" style="border-color:#FFD6E5;">
            <a href="{{ route('store.product.show', $product) }}" class="block">
                <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                    @if($product->image_url)
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        loading="lazy"
                        decoding="async"
                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
                        width="400"
                        height="300" />
                    @else
                    <div class="flex h-full w-full items-center justify-center bg-slate-200">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-slate-400">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    @endif
                    @if($product->sale_price)
                    <span class="absolute left-3 top-3 inline-flex rounded-full bg-rose-500 px-2.5 py-1 text-xs font-semibold text-white">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
                    @endif
                    @if($product->created_at->gt(now()->subDays(14)))
                    <span class="absolute right-3 top-3 inline-flex rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-semibold text-white">New</span>
                    @endif
                </div>
            </a>
            <div class="p-5 sm:p-6">
                @if($product->age_group)
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">{{ $product->age_group }}</p>
                @endif

                <h3 class="mt-2 text-lg font-semibold text-slate-900 transition-colors duration-200 group-hover:text-numnam-700">
                    <a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
                </h3>

                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 100) }}</p>

                <div class="mt-4 flex items-center gap-2">
                    <strong class="text-lg text-slate-900">Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</strong>
                    @if($product->sale_price)
                    <del class="text-sm text-slate-400">Rs {{ number_format($product->price, 0) }}</del>
                    @endif
                </div>

                <form method="POST" action="{{ route('store.cart.add', $product) }}" class="mt-4 flex gap-2">
                    @csrf
                    <button class="flex-1 inline-flex h-10 items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">Add to Cart</button>
                    <button type="button" class="flex-1 inline-flex h-10 items-center justify-center rounded-full border border-numnam-600 text-numnam-600 font-semibold text-sm transition-colors duration-200 hover:bg-numnam-50 buy-now-btn-list" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-price="{{ $product->sale_price ?: $product->price }}">Buy Now</button>
                </form>


            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-4 text-slate-300">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <h3 class="text-xl font-semibold text-slate-900">No products found</h3>
            <p class="mt-2 text-sm text-slate-600">Try adjusting your filters or search terms.</p>
            <a class="mt-5 inline-flex items-center justify-center rounded-full bg-numnam-600 px-5 py-2.5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" href="{{ route('store.products') }}">View All Products</a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $products->links() }}</div>
</section>

<!-- Guest Checkout Modal for Buy Now (Products Listing) -->
<div id="guest-checkout-modal-list" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="background: white; margin: 2rem auto; border-radius: 1.5rem; max-width: 500px; padding: 1.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600; color: #1e293b;">Quick Checkout</h2>
            <button type="button" id="close-modal-list" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">×</button>
        </div>

        <form id="guest-buy-now-form-list" style="display: flex; flex-direction: column; gap: 1rem;">
            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="ship_name" required placeholder="Your full name" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Email</label>
                <input type="email" name="email" required placeholder="your@email.com" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Phone</label>
                <input type="tel" name="ship_phone" required placeholder="10-digit number" pattern="[0-9]{10}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Address</label>
                <input type="text" name="ship_address" required placeholder="Street address" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">City</label>
                    <input type="text" name="ship_city" required placeholder="City" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">State</label>
                    <input type="text" name="ship_state" required placeholder="State" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #475569; margin-bottom: 0.5rem;">Pincode</label>
                <input type="text" name="ship_pincode" required placeholder="6-digit pincode" pattern="[0-9]{6}" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.875rem;">
            </div>

            <p id="modal-message-list" style="font-size: 0.875rem; color: #64748b; display: none;"></p>

            <button type="submit" style="width: 100%; padding: 0.75rem; background: #16a34a; color: white; border: none; border-radius: 2rem; font-weight: 600; cursor: pointer; font-size: 0.875rem;">
                Continue to Payment
            </button>
        </form>
    </div>
</div>

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    // Guest Buy Now for Products Listing
    (function() {
        const modal = document.getElementById('guest-checkout-modal-list');
        const closeBtn = document.getElementById('close-modal-list');
        const form = document.getElementById('guest-buy-now-form-list');
        const message = document.getElementById('modal-message-list');
        const buyNowBtns = document.querySelectorAll('.buy-now-btn-list');

        if (!modal || !buyNowBtns.length) return;

        let selectedProduct = null;

        buyNowBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                selectedProduct = {
                    id: btn.dataset.productId,
                    name: btn.dataset.productName,
                    price: btn.dataset.productPrice,
                };
                modal.style.display = 'flex';
                modal.style.alignItems = 'center';
                modal.style.justifyContent = 'center';
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            form.reset();
            message.style.display = 'none';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                form.reset();
                message.style.display = 'none';
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!selectedProduct) return;

            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            message.style.display = 'none';

            try {
                const response = await fetch('{{ route("store.checkout.guest-payment") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        ship_name: formData.get('ship_name'),
                        ship_phone: formData.get('ship_phone'),
                        ship_address: formData.get('ship_address'),
                        ship_city: formData.get('ship_city'),
                        ship_state: formData.get('ship_state'),
                        ship_pincode: formData.get('ship_pincode'),
                        email: formData.get('email'),
                    }),
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    message.textContent = result.message || 'Failed to create checkout';
                    message.style.color = '#dc2626';
                    message.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    return;
                }

                // Show Razorpay checkout
                if (typeof window.Razorpay === 'undefined') {
                    message.textContent = 'Unable to load Razorpay. Please refresh and try again.';
                    message.style.color = '#dc2626';
                    message.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    return;
                }

                const razorpayOptions = {
                    key: result.key_id,
                    amount: result.amount,
                    currency: result.currency,
                    order_id: result.razorpay_order_id,
                    customer_notification: 1,
                    handler: function(response) {
                        message.textContent = 'Payment successful! Order #' + result.order_number;
                        message.style.color = '#16a34a';
                        message.style.display = 'block';
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;

                        setTimeout(() => {
                            window.location.href = '/shop';
                        }, 2000);
                    },
                    prefill: {
                        name: result.customer_name,
                        email: result.customer_email,
                        contact: result.customer_phone,
                    },
                    theme: {
                        color: '#16a34a'
                    },
                };

                const razorpay = new window.Razorpay(razorpayOptions);
                razorpay.open();
            } catch (error) {
                message.textContent = error.message || 'Unable to process checkout';
                message.style.color = '#dc2626';
                message.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    })();
</script>
@endsection