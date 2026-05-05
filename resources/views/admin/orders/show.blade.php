@extends('admin.layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<section class="admin-panel">
    <h3>Order {{ $order->order_number }}</h3>
    <p class="meta">Placed on {{ $order->created_at->format('d M Y H:i') }}</p>

    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));">
        @csrf
        @method('PUT')
        <select name="status" required>
            @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $status)
            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <select name="payment_status" required>
            @foreach(['pending','paid','failed','refunded'] as $state)
            <option value="{{ $state }}" @selected($order->payment_status === $state)>{{ strtoupper($state) }}</option>
            @endforeach
        </select>
        <input name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Tracking number">
        <input name="notes" value="{{ old('notes', $order->notes) }}" placeholder="Order note">
        <button class="admin-btn" type="submit">Save changes</button>
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
    <h3>Customer & Shipping</h3>
    <div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap: .75rem;">
        <div>
            <strong>Customer Name:</strong>
            <div>{{ $order->user?->name ?? $order->ship_name }}</div>
        </div>
        <div>
            <strong>Customer Email:</strong>
            <div>{{ $order->user?->email ?? 'N/A' }}</div>
        </div>
        <div>
            <strong>Customer Phone:</strong>
            <div>{{ $order->ship_phone ?: ($order->user?->phone ?? 'N/A') }}</div>
        </div>
        <div>
            <strong>Payment:</strong>
            <div>{{ strtoupper($order->payment_gateway ?: $order->payment_method ?: 'N/A') }} ({{ strtoupper($order->payment_status ?: 'PENDING') }})</div>
        </div>
        <div style="grid-column:1 / -1;">
            <strong>Shipping Address:</strong>
            <div>
                {{ $order->ship_name }}, {{ $order->ship_address }}, {{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
            </div>
        </div>
    </div>
</section>

<section class="admin-panel">
    <h3>Payment Timeline</h3>
    <form method="POST" action="{{ route('admin.orders.timeline-note', $order) }}" style="display:flex; gap:.5rem; margin-bottom:.7rem;">
        @csrf
        <input name="note" placeholder="Add admin timeline note" required>
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