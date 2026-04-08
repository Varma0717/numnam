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
        <form method="POST" action="{{ Route::has('admin.orders.bulk') ? route('admin.orders.bulk') : '#' }}" id="bulk-form">
            @csrf
            <div class="admin-bulk-bar">
                <select name="bulk_action">
                    <option value="">Bulk Actions</option>
                    <option value="processing">Mark Processing</option>
                    <option value="shipped">Mark Shipped</option>
                    <option value="delivered">Mark Delivered</option>
                    <option value="cancelled">Mark Cancelled</option>
                </select>
                <button class="admin-btn" type="submit" onclick="return confirm('Apply bulk action to selected orders?')">Apply</button>
                <span class="admin-muted" id="bulk-count"></span>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all"></th>
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
                        <td class="check-column"><input type="checkbox" name="ids[]" value="{{ $order->id }}"></td>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->user?->email ?: 'Guest' }}</td>
                        <td><span class="status-badge status-badge--{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                        <td><span class="status-badge status-badge--{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span> {{ $order->payment_gateway ? '(' . strtoupper($order->payment_gateway) . ')' : '' }}</td>
                        <td>Rs {{ number_format($order->total, 0) }}</td>
                        <td><a class="admin-link" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="admin-empty">
                            <p>No orders found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    {{ $orders->links() }}
</section>
@endsection