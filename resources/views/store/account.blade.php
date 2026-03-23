@extends('store.layouts.app')

@section('title', 'NumNam - My Account')

@section('content')
<section class="section fade-in-up">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <span class="kicker">Welcome back</span>
            <h1 style="margin:0;">{{ auth()->user()->name }}</h1>
        </div>
        <form method="POST" action="{{ route('store.logout') }}">
            @csrf
            <button class="btn-ghost-sm" type="submit">Log Out</button>
        </form>
    </div>
</section>

<section class="section fade-in-up">
    <div class="account-grid">
        <div class="account-stat">
            <strong>{{ $orders->count() }}</strong>
            <span>Orders</span>
        </div>
        <div class="account-stat">
            <strong>{{ $subscriptions->where('status', 'active')->count() }}</strong>
            <span>Active Subscriptions</span>
        </div>
        <div class="account-stat">
            <strong>{{ $referrals->count() }}</strong>
            <span>Referrals</span>
        </div>
        <div class="account-stat">
            <strong>Rs {{ number_format($rewardBalance, 0) }}</strong>
            <span>Reward Balance</span>
        </div>
    </div>
</section>

<section class="section fade-in-up">
    <div class="account-tabs">
        <button class="account-tab active" data-tab="orders">Orders</button>
        <button class="account-tab" data-tab="subscriptions">Subscriptions</button>
        <button class="account-tab" data-tab="referrals">Referrals</button>
        <button class="account-tab" data-tab="rewards">Rewards</button>
    </div>

    {{-- Orders Panel --}}
    <div class="account-panel active" data-panel="orders">
        @if($orders->isEmpty())
            <div class="empty-state">
                <p>You haven't placed any orders yet.</p>
                <a class="cta-btn" href="{{ route('store.products') }}">Start Shopping</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="store-table">
                    <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td><span class="chip">{{ ucfirst($order->status) }}</span></td>
                            <td>Rs {{ number_format($order->total, 0) }}</td>
                            <td>{{ strtoupper($order->payment_status) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Subscriptions Panel --}}
    <div class="account-panel" data-panel="subscriptions">
        @if($subscriptions->isEmpty())
            <div class="empty-state">
                <p>No active subscriptions.</p>
                <a class="cta-btn" href="{{ route('store.pricing') }}">View Plans</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="store-table">
                    <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Cycle</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td><strong>{{ $subscription->plan_name }}</strong></td>
                            <td>{{ ucfirst($subscription->frequency) }}</td>
                            <td>Rs {{ number_format($subscription->price_per_cycle, 0) }}</td>
                            <td><span class="chip">{{ ucfirst($subscription->status) }}</span></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Referrals Panel --}}
    <div class="account-panel" data-panel="referrals">
        <div class="summary-card" style="margin-bottom:24px;">
            <h4 style="margin:0 0 8px;">Your Referral Code</h4>
            <p style="font-size:1.3rem; font-weight:700; letter-spacing:2px; color:var(--brand-1); margin:0;">
                {{ auth()->user()->referral_code ?: 'Not generated yet' }}
            </p>
            @if(auth()->user()->referral_code)
                <p class="meta" style="margin-top:8px;">Share this link: <strong>{{ route('store.register', ['ref' => auth()->user()->referral_code]) }}</strong></p>
            @endif
        </div>
        @if($referrals->isEmpty())
            <p class="meta">No referrals yet. Share your code with friends!</p>
        @else
            <div style="overflow-x:auto;">
                <table class="store-table">
                    <thead>
                    <tr><th>Customer</th><th>Joined</th></tr>
                    </thead>
                    <tbody>
                    @foreach($referrals as $referral)
                        <tr>
                            <td>{{ $referral->name }}</td>
                            <td>{{ $referral->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Rewards Panel --}}
    <div class="account-panel" data-panel="rewards">
        @if($rewards->isEmpty())
            <div class="empty-state">
                <p>No reward transactions yet. Refer friends to earn rewards!</p>
                <a class="cta-btn" href="{{ route('store.refer-friends') }}">Learn About Referrals</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="store-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rewards as $reward)
                        <tr>
                            <td>{{ $reward->created_at->format('d M Y') }}</td>
                            <td><span class="chip">{{ strtoupper($reward->type) }}</span></td>
                            <td>Rs {{ number_format($reward->amount, 0) }}</td>
                            <td>{{ $reward->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
