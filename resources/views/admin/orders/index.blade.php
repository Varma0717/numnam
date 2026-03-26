@extends('admin.layouts.app')

@section('title', 'Orders - Admin')

@section('content')
<section class="admin-panel">
    <h3>Order Management</h3>
    <form method="GET" class="admin-grid" style="grid-template-columns:repeat(3,minmax(0,1fr)); margin-bottom:.7rem;">
        <select name="status">
            <option value="">All status</option>
            @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <select name="payment_status">
            <option value="">All payment states</option>
            @foreach(['pending','paid','failed','refunded'] as $status)
            <option value="{{ $status }}" @selected(request('payment_status')===$status)>{{ strtoupper($status) }}</option>
            @endforeach
        </select>
        <button class="admin-btn" type="submit">Filter</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user?->email ?: 'Guest' }}</td>
                    <td><span class="status-badge status-badge--{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td><span class="status-badge status-badge--{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span> {{ $order->payment_gateway ? '(' . strtoupper($order->payment_gateway) . ')' : '' }}</td>
                    <td>Rs {{ number_format($order->total, 0) }}</td>
                    <td><a class="admin-link" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="admin-empty">
                        <p>No orders found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</section>
@endsection