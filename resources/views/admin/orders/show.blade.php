@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<section class="admin-panel">
    <div class="order-head">
        <div>
            <h3 style="margin:0;">Order {{ $order->order_number }}</h3>
            <p class="order-meta">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="order-head-actions">
            <span class="status-badge status-badge--{{ $order->status }}">{{ ucfirst($order->status) }}</span>
            <span class="status-badge status-badge--{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span>
        </div>
    </div>

    <div class="order-kpis">
        <div class="order-kpi-card">
            <span class="label">Subtotal</span>
            <strong>Rs {{ number_format($order->subtotal, 2) }}</strong>
        </div>
        <div class="order-kpi-card">
            <span class="label">Discount</span>
            <strong>Rs {{ number_format($order->discount, 2) }}</strong>
        </div>
        <div class="order-kpi-card">
            <span class="label">Shipping</span>
            <strong>Rs {{ number_format($order->shipping_fee, 2) }}</strong>
        </div>
        <div class="order-kpi-card">
            <span class="label">Tax</span>
            <strong>Rs {{ number_format($order->tax_amount, 2) }}</strong>
        </div>
        <div class="order-kpi-card order-kpi-card--total">
            <span class="label">Total</span>
            <strong>Rs {{ number_format($order->total, 2) }}</strong>
        </div>
    </div>

    <div class="order-head-delete">
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order and its items? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button class="admin-btn" type="submit" style="background:#b42318;">Delete Order</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px;">
        @csrf
        @method('PUT')

        <div class="admin-form-row">
            <label for="status">Order Status</label>
            <select name="status" id="status" required>
                @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $status)
                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label for="payment_status">Payment Status</label>
            <select name="payment_status" id="payment_status" required>
                @foreach(['pending','paid','failed','refunded'] as $state)
                <option value="{{ $state }}" @selected($order->payment_status === $state)>{{ strtoupper($state) }}</option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label for="tracking_number">Tracking Number</label>
            <input id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Tracking number">
        </div>

        <div class="admin-form-row" style="grid-column:1 / -1;">
            <label for="notes">Internal Notes</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Add internal order note...">{{ old('notes', $order->notes) }}</textarea>
        </div>

        <div style="grid-column:1 / -1;">
            <button class="admin-btn" type="submit">Save Changes</button>
        </div>
    </form>
</section>

<section class="admin-panel">
    <h3>Items</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rs {{ number_format($item->unit_price, 0) }}</td>
                <td>Rs {{ number_format($item->line_total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

<section class="admin-panel">
    <h3>Customer and Shipping</h3>
    <div class="admin-grid order-info-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap: .75rem;">
        <div class="order-info-card">
            <strong>Customer Name</strong>
            <div>{{ $order->user?->name ?? $order->ship_name }}</div>
        </div>
        <div class="order-info-card">
            <strong>Customer Email</strong>
            <div>{{ $order->user?->email ?? 'N/A' }}</div>
        </div>
        <div class="order-info-card">
            <strong>Customer Phone</strong>
            <div>{{ $order->ship_phone ?: ($order->user?->phone ?? 'N/A') }}</div>
        </div>
        <div class="order-info-card">
            <strong>Payment Gateway</strong>
            <div>{{ strtoupper($order->payment_gateway ?: $order->payment_method ?: 'N/A') }}</div>
        </div>
        <div class="order-info-card">
            <strong>Payment Reference</strong>
            <div>{{ $order->payment_reference ?: 'N/A' }}</div>
        </div>
        <div class="order-info-card">
            <strong>Coupon Code</strong>
            <div>{{ $order->coupon_code ?: 'N/A' }}</div>
        </div>
        <div class="order-info-card" style="grid-column:1 / -1;">
            <strong>Shipping Address</strong>
            <div>
                {{ $order->ship_name }}, {{ $order->ship_address }}, {{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
            </div>
        </div>
    </div>
</section>

<section class="admin-panel">
    <h3>Payment Timeline</h3>
    <form method="POST" action="{{ route('admin.orders.timeline-note', $order) }}" style="display:flex; gap:.5rem; margin-bottom:.7rem; flex-wrap:wrap;">
        @csrf
        <input name="note" placeholder="Add admin timeline note" required style="min-width:260px; flex:1;">
        <button class="admin-btn" type="submit">Add</button>
    </form>

    @if($order->paymentEvents->count())
    <ul class="order-timeline">
        @foreach($order->paymentEvents as $event)
        <li>
            <strong>{{ $event->event_type }}</strong> &mdash; <span class="status-badge status-badge--{{ $event->status }}">{{ $event->status }}</span>
            {{ $event->gateway ? '(' . strtoupper($event->gateway) . ')' : '' }}
            @if($event->external_reference) &middot; Ref: {{ $event->external_reference }} @endif
            @if($event->note) &middot; {{ $event->note }} @endif
            <span class="tl-time">{{ $event->created_at->format('d M Y H:i') }}</span>
        </li>
        @endforeach
    </ul>
    @else
    <div class="admin-empty">
        <p>No payment events yet.</p>
    </div>
    @endif
</section>
@endsection