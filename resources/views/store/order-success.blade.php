@extends('store.layouts.app')

@section('title', 'Order Confirmed - NumNam')

@section('content')
{{-- Progress Steps --}}
<section class="section pb-4 pt-6">
    <div class="flex items-center justify-center gap-2 text-xs font-semibold sm:gap-3">
        <span class="flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            Cart
        </span>
        <span class="h-px w-6 bg-emerald-300 sm:w-10"></span>
        <span class="flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            Checkout
        </span>
        <span class="h-px w-6 bg-emerald-300 sm:w-10"></span>
        <span class="rounded-full border border-numnam-300 bg-numnam-600 px-3 py-1 text-white">Confirmation</span>
    </div>
</section>

{{-- Success Banner --}}
<section class="section pb-6">
    <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white px-6 py-10 text-center shadow-sm sm:py-14">
        <span class="inline-flex h-16 w-16 items-center justify-center rounded-full border border-emerald-200 bg-emerald-100 text-emerald-600">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </span>
        <h1 class="mt-5 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Thank You for Your Order!</h1>
        <p class="mt-1 text-sm font-semibold text-slate-500">Order #{{ $order->order_number }}</p>
        <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate-500">We've received your order and will begin processing it soon. You'll receive an email confirmation with tracking details.</p>
        <div class="mt-6 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            @if($order->payment_gateway === 'razorpay' && $order->payment_status !== 'paid')
            <button
                id="rzp-pay-now"
                type="button"
                class="inline-flex h-11 items-center rounded-full bg-slate-900 px-7 text-sm font-semibold text-white transition hover:bg-slate-800"
                data-session-url="{{ route('store.checkout.payment.session', $order) }}"
                data-verify-url="{{ route('store.checkout.payment.verify', $order) }}"
                data-order-number="{{ $order->order_number }}"
                data-customer-name="{{ $order->ship_name }}"
                data-customer-email="{{ $order->user?->email }}"
                data-customer-phone="{{ $order->ship_phone }}">
                Complete Payment
            </button>
            @endif
            <a class="inline-flex h-11 items-center rounded-full bg-numnam-600 px-7 text-sm font-semibold text-white transition hover:bg-numnam-700" href="{{ route('store.account') }}">View My Orders</a>
            <a class="inline-flex h-11 items-center rounded-full border border-slate-200 bg-white px-7 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" href="{{ route('store.products') }}">Continue Shopping</a>
        </div>
    </div>
</section>

{{-- Order Summary --}}
<section class="section pb-14">
    <div class="mx-auto max-w-xl">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Order Details</h3>
            </div>
            <div class="divide-y divide-slate-100 px-6">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-700">{{ $item->product_name }} <span class="text-slate-400">&times; {{ $item->quantity }}</span></span>
                    <strong class="text-sm font-semibold text-slate-900">Rs {{ number_format($item->line_total, 0) }}</strong>
                </div>
                @endforeach
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">Subtotal</span>
                    <strong class="text-sm text-slate-900">Rs {{ number_format($order->subtotal, 0) }}</strong>
                </div>
                @if($order->discount > 0)
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">Discount</span>
                    <strong class="text-sm text-emerald-600">-Rs {{ number_format($order->discount, 0) }}</strong>
                </div>
                @endif
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-slate-500">Shipping</span>
                    <strong class="text-sm text-slate-900">{{ $order->shipping_fee > 0 ? 'Rs ' . number_format($order->shipping_fee, 0) : 'Free' }}</strong>
                </div>
                <div class="flex items-center justify-between py-4">
                    <span class="font-semibold text-slate-900">Total</span>
                    <strong class="text-lg font-extrabold text-numnam-700">Rs {{ number_format($order->total, 0) }}</strong>
                </div>
            </div>
            <div class="border-t border-slate-100 bg-slate-50/60 px-6 py-4 text-sm text-slate-500">
                <p><strong class="text-slate-700">Payment:</strong> {{ strtoupper($order->payment_gateway ?: $order->payment_method ?? 'Pending') }}</p>
                <p class="mt-1"><strong class="text-slate-700">Shipping to:</strong> {{ $order->ship_name }}, {{ $order->ship_address }}, {{ $order->ship_city }} - {{ $order->ship_pincode }}</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@if($order->payment_gateway === 'razorpay' && $order->payment_status !== 'paid')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    (function() {
        const payBtn = document.getElementById('rzp-pay-now');
        if (!payBtn) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const setLoading = (isLoading) => {
            payBtn.disabled = isLoading;
            payBtn.textContent = isLoading ? 'Opening Razorpay...' : 'Complete Payment';
        };

        payBtn.addEventListener('click', async function() {
            if (typeof window.Razorpay === 'undefined') {
                alert('Unable to load Razorpay checkout. Please refresh and try again.');
                return;
            }

            setLoading(true);

            try {
                const sessionResponse = await fetch(payBtn.dataset.sessionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        gateway: 'razorpay'
                    })
                });

                const sessionResult = await sessionResponse.json();
                if (!sessionResponse.ok || !sessionResult.success) {
                    throw new Error(sessionResult.message || 'Unable to initialize payment.');
                }

                const options = {
                    key: sessionResult.publishable_key,
                    amount: sessionResult.data.amount,
                    currency: sessionResult.data.currency || 'INR',
                    name: 'NumNam',
                    description: 'Order ' + payBtn.dataset.orderNumber,
                    order_id: sessionResult.data.id,
                    prefill: {
                        name: payBtn.dataset.customerName || '',
                        email: payBtn.dataset.customerEmail || '',
                        contact: payBtn.dataset.customerPhone || ''
                    },
                    handler: async function(response) {
                        const verifyResponse = await fetch(payBtn.dataset.verifyUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(response)
                        });

                        const verifyResult = await verifyResponse.json();
                        if (!verifyResponse.ok || !verifyResult.success) {
                            throw new Error(verifyResult.message || 'Payment verification failed.');
                        }

                        window.location.reload();
                    }
                };

                const razorpay = new window.Razorpay(options);
                razorpay.on('payment.failed', function(event) {
                    alert(event?.error?.description || 'Payment failed. Please try again.');
                });
                razorpay.open();
            } catch (error) {
                alert(error.message || 'Unable to start payment.');
            } finally {
                setLoading(false);
            }
        });
    })();
</script>
@endif
@endsection