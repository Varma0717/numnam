@extends('store.layouts.app')

@section('title', 'NumNam - Subscription Checkout')
@section('meta_description', 'Complete your NumNam subscription setup. Confirm delivery details and start your subscription.')

@section('content')
<section class="section">
    {{-- Checkout Progress Steps --}}
    <div class="progress-steps">
        <div class="progress-step completed">
            <span class="step-num">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </span>
            <span>Plan Selected</span>
        </div>
        <div class="progress-step-line filled"></div>
        <div class="progress-step active">
            <span class="step-num">2</span>
            <span>Delivery Info</span>
        </div>
        <div class="progress-step-line"></div>
        <div class="progress-step">
            <span class="step-num">3</span>
            <span>Active</span>
        </div>
    </div>
</section>

<section class="section checkout-layout animate-fade-up">
    <form method="POST" action="{{ route('store.subscription.checkout.place-order') }}">
        @csrf
        <h2 class="checkout-section-title">Delivery Information</h2>
        <p class="meta" style="margin-bottom: 20px;">Your subscription will be delivered to this address starting from the next billing cycle.</p>

        <div class="row g-3">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="ship_name">Full Name</label>
                    <input class="input" id="ship_name" name="ship_name" placeholder="Full name" value="{{ old('ship_name', $user->name ?? '') }}" required>
                    @error('ship_name') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="ship_phone">Phone</label>
                    <input class="input" id="ship_phone" name="ship_phone" placeholder="Phone number" value="{{ old('ship_phone') }}" required pattern="[0-9]{10}" title="Enter 10-digit phone number">
                    @error('ship_phone') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="ship_address">Address</label>
            <input class="input" id="ship_address" name="ship_address" placeholder="Street address" value="{{ old('ship_address') }}" required>
            @error('ship_address') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="row g-3">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="ship_city">City</label>
                    <input class="input" id="ship_city" name="ship_city" placeholder="City" value="{{ old('ship_city') }}" required>
                    @error('ship_city') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="ship_state">State</label>
                    <input class="input" id="ship_state" name="ship_state" placeholder="State" value="{{ old('ship_state') }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="ship_pincode">Pincode</label>
            <input class="input" id="ship_pincode" name="ship_pincode" placeholder="Pincode" value="{{ old('ship_pincode') }}" pattern="[0-9]{6}" title="Enter 6-digit pincode">
        </div>

        <h2 class="checkout-section-title" style="margin-top: 40px;">Subscription Confirmation</h2>
        <div class="subscription-terms">
            <p class="meta">
                <strong>This subscription will:</strong>
            </p>
            <ul style="margin-left: 20px; color: var(--muted); line-height: 1.8;">
                <li>Start with your first delivery in the next billing cycle</li>
                <li>Renew automatically every {{ $subscription['frequency'] === 'one_time' ? 'one time' : trim(strtolower(str_replace('_', ' ', $subscription['frequency']))) }}</li>
                <li>Be delivered to the address above</li>
                <li>Can be paused or cancelled anytime from your account</li>
            </ul>
        </div>

        <div class="form-group" style="margin-top: 24px;">
            <label class="checkbox-label">
                <input type="checkbox" name="terms_agreed" required>
                <span>I agree to the <a href="{{ route('store.legal.terms') }}" target="_blank">terms and conditions</a> and <a href="{{ route('store.legal.privacy') }}" target="_blank">privacy policy</a></span>
            </label>
        </div>

        <button class="btn btn-primary checkout-submit-btn" type="submit" style="width: 100%; margin-top: 32px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle; margin-right:6px;">
                <path d="M12 5v14M5 12h14" />
            </svg>
            Activate Subscription
        </button>

        <a href="{{ route('store.pricing') }}" class="btn btn-secondary" style="width: 100%; margin-top: 12px; text-align: center; display: block;">
            Back to Plans
        </a>
    </form>

    <aside class="summary-card checkout-summary">
        <h2 class="order-details-title">Subscription Summary</h2>

        <div class="summary-row">
            <span>Plan</span>
            <strong>{{ $subscription['plan_name'] }}</strong>
        </div>

        <div class="summary-row">
            <span>Billing Frequency</span>
            <strong>{{ $subscription['frequency'] === 'one_time' ? 'One Time' : ucfirst(str_replace('_', ' ', $subscription['frequency'])) }}</strong>
        </div>

        <div class="summary-row total" style="margin-top: 16px;">
            <span>Amount Per Cycle</span>
            <strong>Rs {{ number_format($subscription['price'], 0) }}</strong>
        </div>

        <div class="order-details-footer" style="margin-top: 24px;">
            <p class="meta checkout-trust-line">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Your subscription is secure
            </p>
            <p class="meta checkout-trust-line">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 20.5a7.5 7.5 0 1 0 0-15 7.5 7.5 0 0 0 0 15Z" />
                </svg>
                Cancel anytime, no questions asked
            </p>
        </div>
    </aside>
</section>
@endsection