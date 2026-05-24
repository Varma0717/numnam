<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentEvent;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    public function create()
    {
        $users = User::query()->select('id', 'name', 'email')->orderBy('name')->limit(300)->get();
        $products = Product::query()->select('id', 'name', 'price', 'sale_price')->orderBy('name')->get();

        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'required|in:upi,card,cod,netbanking',
            'payment_gateway' => 'nullable|string|max:40',
            'payment_reference' => 'nullable|string|max:120',
            'coupon_code' => 'nullable|string|max:120',
            'discount' => 'nullable|numeric|min:0',
            'shipping_fee' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'ship_name' => 'required|string|max:255',
            'ship_phone' => 'required|string|max:30',
            'ship_address' => 'required|string|max:500',
            'ship_city' => 'required|string|max:120',
            'ship_state' => 'required|string|max:120',
            'ship_pincode' => 'required|string|max:20',
            'tracking_number' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:1200',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'items.*.unit_price' => 'nullable|numeric|min:0',
        ]);

        $order = DB::transaction(function () use ($data) {
            $discount = (float) ($data['discount'] ?? 0);
            $shippingFee = (float) ($data['shipping_fee'] ?? 0);
            $taxAmount = (float) ($data['tax_amount'] ?? 0);

            $subtotal = 0.0;

            $order = Order::create([
                'user_id' => $data['user_id'] ?? null,
                'status' => $data['status'],
                'payment_status' => $data['payment_status'],
                'payment_method' => $data['payment_method'],
                'payment_gateway' => $data['payment_gateway'] ?? null,
                'payment_reference' => $data['payment_reference'] ?? null,
                'coupon_code' => $data['coupon_code'] ?? null,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'tax_amount' => $taxAmount,
                'ship_name' => $data['ship_name'],
                'ship_phone' => $data['ship_phone'],
                'ship_address' => $data['ship_address'],
                'ship_city' => $data['ship_city'],
                'ship_state' => $data['ship_state'],
                'ship_pincode' => $data['ship_pincode'],
                'tracking_number' => $data['tracking_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'subtotal' => 0,
                'total' => 0,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::query()->findOrFail($item['product_id']);
                $unitPrice = isset($item['unit_price'])
                    ? (float) $item['unit_price']
                    : (float) ($product->sale_price ?? $product->price ?? 0);

                $qty = (int) $item['quantity'];
                $lineTotal = $unitPrice * $qty;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'line_total' => $lineTotal,
                ]);
            }

            $total = max(0, $subtotal - $discount + $shippingFee + $taxAmount);
            $order->update([
                'subtotal' => $subtotal,
                'total' => $total,
            ]);

            PaymentEvent::create([
                'order_id' => $order->id,
                'gateway' => $order->payment_gateway ?: 'manual',
                'event_type' => 'admin.order.created',
                'external_reference' => $order->payment_reference,
                'status' => $order->payment_status,
                'amount' => $order->total,
                'currency' => 'INR',
                'signature_valid' => true,
                'note' => 'Order created from admin panel',
                'payload' => [
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                ],
            ]);

            return $order;
        });

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order created successfully.');
    }

    public function index(Request $request)
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest('id')
            ->paginate(20);

        $orders->appends($request->query());

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'paymentEvents' => fn($query) => $query->latest('id')]);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return redirect()->route('admin.orders.show', $order);
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

    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            PaymentEvent::create([
                'order_id' => $order->id,
                'gateway' => $order->payment_gateway ?: 'manual',
                'event_type' => 'admin.order.deleted',
                'external_reference' => $order->payment_reference,
                'status' => $order->payment_status,
                'amount' => $order->total,
                'currency' => 'INR',
                'signature_valid' => true,
                'note' => 'Order deleted from admin panel',
                'payload' => [
                    'order_number' => $order->order_number,
                ],
            ]);

            $order->delete();
        });

        return redirect()->route('admin.orders.index')->with('status', 'Order deleted successfully.');
    }
}
