@extends('store.layouts.app')

@section('title', 'Complete Payment - NumNam')
@section('meta_description', 'Complete your secure Razorpay payment to confirm your NumNam order.')

@section('content')
<section class="section pb-4 pt-6">
    <div class="mx-auto max-w-2xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-numnam-600">Payment Step</p>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Complete Your Payment</h1>
        <p class="mt-2 text-sm text-slate-600">
            Order #{{ $order->order_number }} is created. Please complete Razorpay payment to confirm this order.
        </p>

        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-center justify-between text-sm text-slate-600">
                <span>Order Total</span>
                <strong class="text-lg text-slate-900">Rs {{ number_format($order->total, 0) }}</strong>
            </div>
            <div class="mt-2 flex items-center justify-between text-sm text-slate-600">
                <span>Payment Method</span>
                <strong class="text-slate-900">RAZORPAY</strong>
            </div>
            <div class="mt-2 flex items-center justify-between text-sm text-slate-600">
                <span>Payment Status</span>
                <strong class="text-amber-700">{{ strtoupper($order->payment_status ?? 'PENDING') }}</strong>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button
                id="rzp-pay-now"
                type="button"
                class="inline-flex h-11 items-center justify-center rounded-full bg-numnam-600 px-7 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700"
                data-session-url="{{ route('store.checkout.payment.session', $order) }}"
                data-verify-url="{{ route('store.checkout.payment.verify', $order) }}"
                data-success-url="{{ route('store.order.success', $order) }}"
                data-order-number="{{ $order->order_number }}"
                data-customer-name="{{ $order->ship_name }}"
                data-customer-email="{{ $order->user?->email }}"
                data-customer-phone="{{ $order->ship_phone }}"
                data-razorpay-key="{{ config('services.razorpay.key_id') }}"
                data-razorpay-order-id="{{ $order->payment_reference }}"
                data-razorpay-amount="{{ (int) round(((float) $order->total) * 100) }}"
                data-razorpay-currency="INR">
                Pay Now
            </button>

            <a href="{{ route('store.cart') }}" class="inline-flex h-11 items-center justify-center rounded-full border border-slate-200 bg-white px-7 text-sm font-semibold text-slate-700 transition-colors duration-200 hover:bg-slate-50">
                Back to Cart
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    (function() {
        const payBtn = document.getElementById('rzp-pay-now');
        if (!payBtn) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        const setLoading = (isLoading) => {
            payBtn.disabled = isLoading;
            payBtn.textContent = isLoading ? 'Opening Razorpay...' : 'Pay Now';
        };

        const openPayment = async () => {
            if (typeof window.Razorpay === 'undefined') {
                alert('Unable to load Razorpay checkout. Please refresh and try again.');
                return;
            }

            setLoading(true);

            try {
                let orderId = payBtn.dataset.razorpayOrderId || '';
                let amount = Number(payBtn.dataset.razorpayAmount || 0);
                let currency = payBtn.dataset.razorpayCurrency || 'INR';
                const razorpayKey = payBtn.dataset.razorpayKey || '';

                if (!orderId || !amount) {
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

                    orderId = sessionResult.data.id;
                    amount = Number(sessionResult.data.amount || 0);
                    currency = sessionResult.data.currency || 'INR';
                }

                if (!razorpayKey || !orderId || !amount) {
                    throw new Error('Razorpay checkout details are incomplete.');
                }

                const options = {
                    key: razorpayKey,
                    amount: amount,
                    currency: currency,
                    name: 'NumNam',
                    description: 'Order ' + payBtn.dataset.orderNumber,
                    order_id: orderId,
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

                        window.location.href = payBtn.dataset.successUrl;
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
        };

        payBtn.addEventListener('click', openPayment);
    })();
</script>
@endsection