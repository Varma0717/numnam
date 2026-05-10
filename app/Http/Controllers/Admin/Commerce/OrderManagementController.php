<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'paymentEvents' => fn($query) => $query->latest('id')]);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:1200',
        ]);

        $order->update($data);

        PaymentEvent::create([
            'order_id' => $order->id,
            'gateway' => $order->payment_gateway ?: 'manual',
            'event_type' => 'admin.order.updated',
            'external_reference' => $order->payment_reference,
            'status' => $order->payment_status,
            'amount' => $order->total,
            'currency' => 'INR',
            'signature_valid' => true,
            'note' => 'Order updated from admin panel',
            'payload' => $data,
        ]);

        return back()->with('status', 'Order updated successfully.');
    }

    public function addTimelineNote(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'note' => 'required|string|max:500',
        ]);

        PaymentEvent::create([
            'order_id' => $order->id,
            'gateway' => $order->payment_gateway ?: 'manual',
            'event_type' => 'admin.timeline.note',
            'external_reference' => $order->payment_reference,
            'status' => $order->payment_status,
            'amount' => $order->total,
            'currency' => 'INR',
            'signature_valid' => true,
            'note' => $data['note'],
            'payload' => ['source' => 'admin'],
        ]);

        return back()->with('status', 'Timeline note added.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $request->validate([
            'bulk_action' => 'required|in:processing,shipped,delivered,cancelled',
            'ids'         => 'required|array',
            'ids.*'       => 'integer|exists:orders,id',
        ]);

        $ids = $request->input('ids');
        Order::whereIn('id', $ids)->update(['status' => $request->input('bulk_action')]);

        return redirect()->route('admin.orders.index')->with('status', 'Bulk action applied to ' . count($ids) . ' orders.');
    }
}
