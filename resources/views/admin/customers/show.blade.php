@extends('admin.layouts.app')

@section('title', 'Customer: ' . $customer->name . ' - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>{{ $customer->name }}</h2>
        <p class="admin-desc">{{ $customer->email }} &middot; Joined {{ $customer->created_at->format('d M Y') }}</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="admin-btn-secondary" style="text-decoration:none;">&larr; Back to Customers</a>
</div>

<div class="admin-grid metrics-grid" style="margin-bottom:20px;">
    <div class="metric-card">
        <strong>{{ $customer->orders_count }}</strong>
        <span>Orders</span>
    </div>
    <div class="metric-card">
        <strong>₹{{ number_format($totalSpent, 0) }}</strong>
        <span>Total Spent</span>
    </div>
    <div class="metric-card">
        <strong>{{ $customer->reviews_count }}</strong>
        <span>Reviews</span>
    </div>
    <div class="metric-card">
        <strong>{{ $customer->referral_code ?: '—' }}</strong>
        <span>Referral Code</span>
    </div>
</div>

<section class="admin-panel">
    <h3 style="padding:10px 12px; margin:0; border-bottom:1px solid var(--wp-border); font-size:14px; font-weight:600;">Recent Orders</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td><span class="status-badge status-badge--{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td><span class="status-badge status-badge--{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td>₹{{ number_format($order->total, 0) }}</td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="admin-link">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="admin-empty">No orders yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection