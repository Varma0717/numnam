@extends('admin.layouts.app')

@section('title', 'Admin Dashboard - NumNam')

@section('content')
<div class="admin-page-header">
    <h2>Dashboard</h2>
    <p class="admin-desc">Welcome back! Here's what's happening with NumNam.</p>
</div>

<section class="admin-grid metrics-grid">
    <article class="metric-card">
        <span>Total Revenue</span>
        <strong>Rs {{ number_format($metrics['revenue'] ?? 0, 0) }}</strong>
    </article>
    <article class="metric-card">
        <span>Orders</span>
        <strong>{{ $metrics['orders'] }}</strong>
    </article>
    <article class="metric-card">
        <span>Products</span>
        <strong>{{ $metrics['products'] }}</strong>
    </article>
    <article class="metric-card">
        <span>Customers</span>
        <strong>{{ $metrics['customers'] ?? 0 }}</strong>
    </article>
    <article class="metric-card">
        <span>Subscriptions</span>
        <strong>{{ $metrics['subscriptions'] }}</strong>
    </article>
    <article class="metric-card">
        <span>Blog Posts</span>
        <strong>{{ $metrics['blogs'] }}</strong>
    </article>
    <article class="metric-card">
        <span>Messages</span>
        <strong>{{ $metrics['contacts'] }}</strong>
    </article>
    <article class="metric-card">
        <span>Reward Credits</span>
        <strong>Rs {{ number_format($metrics['reward_credits'], 0) }}</strong>
    </article>
</section>

<div class="admin-grid" style="grid-template-columns: 1fr 1fr; gap:20px;">
    <section class="admin-panel">
        <h3>Recent Orders</h3>
        <table class="admin-table">
            <thead>
            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>Rs {{ number_format($order->total, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>

    <section class="admin-panel">
        <h3>Quick Actions</h3>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:12px;">
            <a href="{{ route('admin.products.create') }}" class="admin-quick-btn">+ New Product</a>
            <a href="{{ route('admin.coupons.create') }}" class="admin-quick-btn">+ New Coupon</a>
            <a href="{{ route('admin.orders.index') }}" class="admin-quick-btn">View Orders</a>
            <a href="{{ route('admin.customers.index') }}" class="admin-quick-btn">View Customers</a>
            <a href="{{ route('admin.contacts.index') }}" class="admin-quick-btn">View Messages</a>
            <a href="{{ route('admin.settings.index') }}" class="admin-quick-btn">Site Settings</a>
        </div>
    </section>
</div>
@endsection
