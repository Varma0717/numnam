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
        <tr><th>Product</th><th>Qty</th><th>Unit</th><th>Total</th></tr>
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
    <h3>Payment Timeline</h3>
    <form method="POST" action="{{ route('admin.orders.timeline-note', $order) }}" style="display:flex; gap:.5rem; margin-bottom:.7rem;">
        @csrf
        <input name="note" placeholder="Add admin timeline note" required>
        <button class="admin-btn" type="submit">Add</button>
    </form>

    <table class="admin-table">
        <thead>
        <tr><th>Time</th><th>Gateway</th><th>Event</th><th>Status</th><th>Reference</th><th>Note</th></tr>
        </thead>
        <tbody>
        @forelse($order->paymentEvents as $event)
            <tr>
                <td>{{ $event->created_at->format('d M Y H:i') }}</td>
                <td>{{ strtoupper($event->gateway) }}</td>
                <td>{{ $event->event_type }}</td>
                <td>{{ $event->status }}</td>
                <td>{{ $event->external_reference }}</td>
                <td>{{ $event->note }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No payment events yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
