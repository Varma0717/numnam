@extends('store.layouts.app')

@section('title', 'NumNam - My Account')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">My Account</span>
        <h1>Orders, subscriptions, and referrals</h1>
        <p>Customer portal experience with referral-ready account details.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <h3>Referral Program</h3>
    <p class="meta">Your referral code: <strong>{{ auth()->user()->referral_code ?: 'Not generated yet' }}</strong></p>
    @if(auth()->user()->referral_code)
        <p class="meta">Referral link: {{ route('store.register', ['ref' => auth()->user()->referral_code]) }}</p>
    @endif
    <p class="meta">Referred customers: {{ $referrals->count() }}</p>
    <p class="meta">Reward wallet balance: <strong>Rs {{ number_format($rewardBalance, 0) }}</strong></p>
</section>

<section class="section">
    <h3>Reward Ledger</h3>
    @if($rewards->isEmpty())
        <p class="meta">No reward transactions yet.</p>
    @else
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
                    <td>{{ strtoupper($reward->type) }}</td>
                    <td>Rs {{ number_format($reward->amount, 0) }}</td>
                    <td>{{ $reward->description }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</section>

<section class="section">
    <h3>Recent Orders</h3>
    @if($orders->isEmpty())
        <p class="meta">No orders yet.</p>
    @else
        <table class="store-table">
            <thead>
            <tr>
                <th>Order #</th>
                <th>Status</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Gateway</th>
                <th>Reference</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>Rs {{ number_format($order->total, 0) }}</td>
                    <td>{{ strtoupper($order->payment_status) }}</td>
                    <td>{{ $order->payment_gateway ? strtoupper($order->payment_gateway) : '-' }}</td>
                    <td>{{ $order->payment_reference ?: '-' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</section>

<section class="section">
    <h3>Subscriptions</h3>
    @if($subscriptions->isEmpty())
        <p class="meta">No active subscriptions.</p>
    @else
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
                    <td>{{ $subscription->plan_name }}</td>
                    <td>{{ ucfirst($subscription->frequency) }}</td>
                    <td>Rs {{ number_format($subscription->price_per_cycle, 0) }}</td>
                    <td>{{ ucfirst($subscription->status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</section>
@endsection
