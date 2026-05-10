@extends('store.layouts.app')

@section('title', 'NumNam - Checkout')
@section('meta_description', 'Complete your NumNam order. Secure checkout with Razorpay and Cash on Delivery.')

@section('content')
<section class="section pb-4 pt-2">
    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.12em] text-slate-500 sm:text-sm">
        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-emerald-700">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            Cart
        </span>
        <span class="h-px w-8 bg-slate-300"></span>
        <span class="inline-flex items-center rounded-full border border-numnam-200 bg-numnam-50 px-3 py-1 text-numnam-700">2 Checkout</span>
        <span class="h-px w-8 bg-slate-300"></span>
        <span class="inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1">3 Confirmation</span>
    </div>
</section>

<section class="section pt-4">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
        <form id="checkout-form" method="POST" action="{{ route('store.checkout.place-order') }}" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-7">
            @csrf
            <h2 class="text-xl font-semibold tracking-tight text-slate-900">Shipping Information</h2>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label for="ship_name" class="mb-1 block text-sm font-medium text-slate-700">Full Name</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_name" name="ship_name" placeholder="Full name" value="{{ old('ship_name', auth()->user()->name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="ship_phone" class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_phone" name="ship_phone" placeholder="Phone number" value="{{ old('ship_phone') }}" required pattern="[0-9]{10}" title="Enter 10-digit phone number">
                </div>
            </div>
            <div class="mt-4 form-group">
                <label for="ship_address" class="mb-1 block text-sm font-medium text-slate-700">Address</label>
                <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_address" name="ship_address" placeholder="Street address" value="{{ old('ship_address') }}" required>
            </div>
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label for="ship_city" class="mb-1 block text-sm font-medium text-slate-700">City</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_city" name="ship_city" placeholder="City" value="{{ old('ship_city') }}" required>
                </div>
                <div class="form-group">
                    <label for="ship_state" class="mb-1 block text-sm font-medium text-slate-700">State</label>
                    <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_state" name="ship_state" placeholder="State" value="{{ old('ship_state') }}" required>
                </div>
            </div>
            <div class="mt-4 form-group">
                <label for="ship_pincode" class="mb-1 block text-sm font-medium text-slate-700">Pincode</label>
                <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="ship_pincode" name="ship_pincode" placeholder="Pincode" value="{{ old('ship_pincode') }}" required pattern="[0-9]{6}" title="Enter 6-digit pincode">
            </div>

            <h2 class="mt-8 text-xl font-semibold tracking-tight text-slate-900">Payment Method</h2>
            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                @php
                $paymentSettings = \App\Models\SiteSetting::whereIn('key', [
                'payment_razorpay_enabled', 'payment_cod_enabled',
                'payment_cod_min_order', 'payment_cod_max_order', 'payment_cod_allowed_pincodes',
                ])->pluck('value', 'key');

                $methods = [];

                // Razorpay is always available (primary gateway)
                if (($paymentSettings['payment_razorpay_enabled'] ?? '1') === '1') {
                $methods[] = ['value' => 'razorpay', 'label' => 'Razorpay', 'desc' => 'UPI, Cards, Wallets', 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" />
                    <line x1="1" y1="10" x2="23" y2="10" />
                </svg>'];
                }

                // COD: only if admin-enabled AND order meets conditions
                $codEnabled = ($paymentSettings['payment_cod_enabled'] ?? '0') === '1';
                $codMinOrder = (float) ($paymentSettings['payment_cod_min_order'] ?? 0);
                $codMaxOrder = (float) ($paymentSettings['payment_cod_max_order'] ?? 0);
                $codPincodes = array_filter(array_map('trim', explode(',', $paymentSettings['payment_cod_allowed_pincodes'] ?? '')));
                $orderTotal = $totals['total'] ?? 0;

                $codAvailable = $codEnabled;
                if ($codAvailable && $codMinOrder > 0 && $orderTotal < $codMinOrder) {
                    $codAvailable=false;
                    }
                    if ($codAvailable && $codMaxOrder> 0 && $orderTotal > $codMaxOrder) {
                    $codAvailable = false;
                    }
                    // Pincode check deferred to JS / server-side on submit

                    if ($codAvailable) {
                    $codDesc = 'Pay when delivered';
                    if ($codMinOrder > 0) $codDesc .= ' · Min ₹' . number_format($codMinOrder, 0);
                    $methods[] = ['value' => 'cod', 'label' => 'Cash on Delivery', 'desc' => $codDesc, 'icon' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>'];
                    }
                    @endphp
                    @foreach($methods as $method)
                    <label class="relative block cursor-pointer">
                        <input class="peer sr-only" type="radio" name="payment_method" value="{{ $method['value'] }}" {{ old('payment_method') === $method['value'] ? 'checked' : '' }} required>
                        <span class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 transition-all duration-200 peer-checked:border-numnam-300 peer-checked:bg-numnam-50">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-700 peer-checked:bg-white">{!! $method['icon'] !!}</span>
                            <span>
                                <strong class="block text-sm text-slate-900">{{ $method['label'] }}</strong>
                                <span class="text-xs text-slate-600">{{ $method['desc'] }}</span>
                            </span>
                        </span>
                    </label>
                    @endforeach
                    @if(empty($methods))
                    <p class="text-sm text-red-600">No payment methods available. Please contact support.</p>
                    @endif
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="form-group">
                    <label for="coupon_code" class="mb-1 block text-sm font-medium text-slate-700">Coupon Code</label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input class="h-11 w-full rounded-xl border border-slate-200 bg-white px-3.5 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" value="{{ old('coupon_code') }}">
                        <button type="button" id="coupon-apply-btn" class="inline-flex h-11 shrink-0 items-center justify-center rounded-xl border border-numnam-200 bg-numnam-50 px-4 text-sm font-semibold text-numnam-700 transition-colors duration-200 hover:border-numnam-300 hover:bg-numnam-100">
                            Apply
                        </button>
                    </div>
                    <p id="coupon-feedback" class="text-sm text-slate-500" aria-live="polite"></p>
                </div>
                <div class="form-group">
                    @if(auth()->check() && auth()->user()->referred_by)
                    <label class="mb-1 block text-sm font-medium text-transparent">Referral</label>
                    <p class="inline-flex items-center gap-1.5 text-sm text-emerald-700">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Referral welcome discount auto-applied
                    </p>
                    @endif
                </div>
            </div>

            <div class="mt-4 form-group">
                <label for="notes" class="mb-1 block text-sm font-medium text-slate-700">Order Notes (optional)</label>
                <textarea id="notes" name="notes" class="min-h-[120px] w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 outline-none transition-colors duration-200 placeholder:text-slate-400 focus:border-numnam-400" placeholder="Special instructions for delivery...">{{ old('notes') }}</textarea>
            </div>

            <button class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-full bg-numnam-600 px-5 text-sm font-semibold text-white transition-colors duration-200 hover:bg-numnam-700" type="submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="mr-1.5">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                    <path d="M7 11V7a5 5 0 0110 0v4" />
                </svg>
                Place Secure Order
            </button>
        </form>

        <aside class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
            <h2 class="text-lg font-semibold text-slate-900">Order Summary</h2>
            @foreach($items as $item)
            <div class="mt-3 flex items-center justify-between gap-3 text-sm text-slate-600">
                <span>
                    {{ $item['product']->name }}
                    <span class="text-xs text-slate-500"> &times; {{ $item['qty'] }}</span>
                </span>
                <strong class="text-slate-900">Rs {{ number_format($item['line_total'], 0) }}</strong>
            </div>
            @endforeach
            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4 text-sm text-slate-600"><span>Subtotal</span><strong id="summary-subtotal" class="text-slate-900">Rs {{ number_format($summary['subtotal'], 0) }}</strong></div>
            <div class="mt-2 flex items-center justify-between text-sm text-slate-600"><span>Shipping</span><strong id="summary-shipping" class="text-slate-900">{{ $summary['shipping_fee'] > 0 ? 'Rs ' . number_format($summary['shipping_fee'], 0) : 'Free' }}</strong></div>
            <div id="summary-coupon-row" class="mt-2 flex items-center justify-between text-sm text-emerald-700 {{ $summary['coupon_discount'] > 0 ? '' : 'hidden' }}"><span>Coupon Discount</span><strong id="summary-coupon-discount">- Rs {{ number_format($summary['coupon_discount'], 0) }}</strong></div>
            <div id="summary-referral-row" class="mt-2 flex items-center justify-between text-sm text-emerald-700 {{ $summary['referral_discount'] > 0 ? '' : 'hidden' }}"><span>Referral Discount</span><strong id="summary-referral-discount">- Rs {{ number_format($summary['referral_discount'], 0) }}</strong></div>
            <div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-4"><span class="text-base font-semibold text-slate-900">Total</span><strong id="summary-total" class="text-xl text-slate-900">Rs {{ number_format($summary['total'], 0) }}</strong></div>

            <div class="mt-5 space-y-2 border-t border-slate-200 pt-4">
                <p class="inline-flex items-center gap-1.5 text-sm text-slate-600">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                    Secure SSL encrypted checkout
                </p>
                <p class="inline-flex items-center gap-1.5 text-sm text-slate-600">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                        <polyline points="17 6 23 6 23 12" />
                    </svg>
                    7-day return policy
                </p>
            </div>
        </aside>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    (function() {
        const checkoutForm = document.getElementById('checkout-form');
        if (!checkoutForm) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const submitBtn = checkoutForm.querySelector('button[type="submit"]');
        const defaultBtnText = submitBtn ? submitBtn.textContent : 'Place Secure Order';
        const couponInput = document.getElementById('coupon_code');
        const couponApplyBtn = document.getElementById('coupon-apply-btn');
        const couponFeedback = document.getElementById('coupon-feedback');
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryShipping = document.getElementById('summary-shipping');
        const summaryCouponRow = document.getElementById('summary-coupon-row');
        const summaryCouponDiscount = document.getElementById('summary-coupon-discount');
        const summaryReferralRow = document.getElementById('summary-referral-row');
        const summaryReferralDiscount = document.getElementById('summary-referral-discount');
        const summaryTotal = document.getElementById('summary-total');

        const formatMoney = (amount) => {
            const numericAmount = Number(amount || 0);
            return 'Rs ' + Math.round(numericAmount).toLocaleString('en-IN');
        };

        const setCouponFeedback = (message, isError = false) => {
            if (!couponFeedback) {
                return;
            }

            couponFeedback.textContent = message || '';
            couponFeedback.classList.toggle('text-red-600', isError);
            couponFeedback.classList.toggle('text-emerald-700', !isError && !!message);
            couponFeedback.classList.toggle('text-slate-500', !message);
        };

        const updateSummary = (summary) => {
            if (!summary) {
                return;
            }

            if (summarySubtotal) {
                summarySubtotal.textContent = formatMoney(summary.subtotal);
            }

            if (summaryShipping) {
                summaryShipping.textContent = Number(summary.shipping_fee || 0) > 0 ? formatMoney(summary.shipping_fee) : 'Free';
            }

            if (summaryCouponRow && summaryCouponDiscount) {
                const couponDiscount = Number(summary.coupon_discount || 0);
                summaryCouponRow.classList.toggle('hidden', couponDiscount <= 0);
                summaryCouponDiscount.textContent = '- ' + formatMoney(couponDiscount);
            }

            if (summaryReferralRow && summaryReferralDiscount) {
                const referralDiscount = Number(summary.referral_discount || 0);
                summaryReferralRow.classList.toggle('hidden', referralDiscount <= 0);
                summaryReferralDiscount.textContent = '- ' + formatMoney(referralDiscount);
            }

            if (summaryTotal) {
                summaryTotal.textContent = formatMoney(summary.total);
            }
        };

        const applyCouponPreview = async () => {
            if (!couponApplyBtn || !couponInput) {
                return;
            }

            couponApplyBtn.disabled = true;
            couponApplyBtn.textContent = 'Applying...';
            setCouponFeedback('');

            try {
                const response = await fetch("{{ route('store.checkout.coupon-preview') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        coupon_code: couponInput.value,
                    }),
                });

                const result = await response.json();
                updateSummary(result.summary);

                if (!response.ok || !result.success) {
                    setCouponFeedback(result.message || 'Unable to apply coupon.', true);
                    return;
                }

                if (result.discounts?.coupon_code) {
                    couponInput.value = result.discounts.coupon_code;
                }

                setCouponFeedback(result.message || 'Coupon applied successfully.');
            } catch (error) {
                setCouponFeedback(error.message || 'Unable to apply coupon.', true);
            } finally {
                couponApplyBtn.disabled = false;
                couponApplyBtn.textContent = 'Apply';
            }
        };

        if (couponApplyBtn) {
            couponApplyBtn.addEventListener('click', applyCouponPreview);
        }

        if (couponInput) {
            couponInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    applyCouponPreview();
                }
            });
        }

        const setLoading = (isLoading, label = 'Processing...') => {
            if (!submitBtn) return;
            submitBtn.disabled = isLoading;
            submitBtn.textContent = isLoading ? label : defaultBtnText;
        };

        checkoutForm.addEventListener('submit', async function(event) {
            const selectedPayment = checkoutForm.querySelector('input[name="payment_method"]:checked')?.value;

            if (selectedPayment !== 'razorpay') {
                return;
            }

            event.preventDefault();

            if (typeof window.Razorpay === 'undefined') {
                alert('Unable to load Razorpay checkout. Please refresh and try again.');
                return;
            }

            setLoading(true, 'Creating order...');

            try {
                const formData = new FormData(checkoutForm);
                const response = await fetch(checkoutForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    if (result?.message) {
                        throw new Error(result.message);
                    }

                    if (result?.errors) {
                        const firstError = Object.values(result.errors)[0];
                        if (Array.isArray(firstError) && firstError.length) {
                            throw new Error(firstError[0]);
                        }
                    }

                    throw new Error('Unable to create order. Please try again.');
                }

                const options = {
                    key: result.razorpay_key,
                    amount: result.amount,
                    currency: result.currency || 'INR',
                    name: 'NumNam',
                    description: 'Order ' + result.order_number,
                    order_id: result.razorpay_order_id,
                    prefill: {
                        name: checkoutForm.querySelector('#ship_name')?.value || '',
                        email: '{{ auth()->user()?->email }}',
                        contact: checkoutForm.querySelector('#ship_phone')?.value || '',
                    },
                    handler: async function(paymentResponse) {
                        const verifyResponse = await fetch(result.verify_url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(paymentResponse),
                        });

                        const verifyResult = await verifyResponse.json();

                        if (!verifyResponse.ok || !verifyResult.success) {
                            throw new Error(verifyResult.message || 'Payment verification failed.');
                        }

                        window.location.href = result.success_url;
                    },
                };

                const razorpay = new window.Razorpay(options);
                razorpay.on('payment.failed', function(eventObj) {
                    alert(eventObj?.error?.description || 'Payment failed. Please try again.');
                });
                setLoading(false);
                razorpay.open();
            } catch (error) {
                setLoading(false);
                alert(error.message || 'Unable to proceed with payment.');
            }
        });
    })();
</script>
@endsection