@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - NumNam')

@section('content')
<section class="admin-grid metrics-grid">
    <article class="metric-card"><strong>{{ $metrics['products'] }}</strong><span>Products</span></article>
    <article class="metric-card"><strong>{{ $metrics['orders'] }}</strong><span>Orders</span></article>
    <article class="metric-card"><strong>{{ $metrics['subscriptions'] }}</strong><span>Subscriptions</span></article>
    <article class="metric-card"><strong>{{ $metrics['contacts'] }}</strong><span>Contacts</span></article>
    <article class="metric-card"><strong>{{ $metrics['blogs'] }}</strong><span>Blogs</span></article>
    <article class="metric-card"><strong>{{ $metrics['coupons'] }}</strong><span>Coupons</span></article>
    <article class="metric-card"><strong>Rs {{ number_format($metrics['reward_credits'], 0) }}</strong><span>Reward Credits</span></article>
</section>

<section class="admin-panel">
    <h3>Recent Orders</h3>
    <table class="admin-table">
        <thead>
        <tr>
            <th>Order</th>
            <th>Status</th>
            <th>Total</th>
            <th>Payment</th>
        </tr>
        </thead>
        <tbody>
        @forelse($recentOrders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td>Rs {{ number_format($order->total, 0) }}</td>
                <td>{{ strtoupper($order->payment_status) }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No orders yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
