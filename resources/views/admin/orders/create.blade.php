@extends('admin.layouts.app')

@section('title', 'Create Order - Admin')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px;">
        <h3>Create Order</h3>
        <a class="admin-link" href="{{ route('admin.orders.index') }}">Back to Orders</a>
    </div>

    <form method="POST" action="{{ route('admin.orders.store') }}" class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px;">
        @csrf

        <div class="admin-form-row">
            <label>Customer (optional)</label>
            <select name="user_id">
                <option value="">Guest Checkout</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('user_id')==$user->id)>
                    {{ $user->name }} ({{ $user->email }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label>Status</label>
            <select name="status" required>
                @foreach(['pending','processing','shipped','delivered','cancelled','refunded'] as $status)
                <option value="{{ $status }}" @selected(old('status', 'pending' )===$status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label>Payment Status</label>
            <select name="payment_status" required>
                @foreach(['pending','paid','failed','refunded'] as $state)
                <option value="{{ $state }}" @selected(old('payment_status', 'pending' )===$state)>{{ strtoupper($state) }}</option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label>Payment Method</label>
            <select name="payment_method" required>
                @foreach(['cod','upi','card','netbanking'] as $method)
                <option value="{{ $method }}" @selected(old('payment_method', 'cod' )===$method)>{{ strtoupper($method) }}</option>
                @endforeach
            </select>
        </div>

        <div class="admin-form-row">
            <label>Payment Gateway (optional)</label>
            <input type="text" name="payment_gateway" value="{{ old('payment_gateway') }}" placeholder="e.g. razorpay">
        </div>

        <div class="admin-form-row">
            <label>Payment Reference (optional)</label>
            <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="Gateway reference">
        </div>

        <div class="admin-form-row">
            <label>Coupon Code (optional)</label>
            <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="e.g. WELCOME10">
        </div>

        <div class="admin-form-row">
            <label>Discount</label>
            <input type="number" min="0" step="0.01" name="discount" value="{{ old('discount', 0) }}">
        </div>

        <div class="admin-form-row">
            <label>Shipping Fee</label>
            <input type="number" min="0" step="0.01" name="shipping_fee" value="{{ old('shipping_fee', 0) }}">
        </div>

        <div class="admin-form-row">
            <label>Tax Amount</label>
            <input type="number" min="0" step="0.01" name="tax_amount" value="{{ old('tax_amount', 0) }}">
        </div>

        <div class="admin-form-row" style="grid-column:1 / -1;">
            <h4 style="margin:4px 0 8px;">Shipping Details</h4>
        </div>

        <div class="admin-form-row">
            <label>Recipient Name</label>
            <input type="text" name="ship_name" value="{{ old('ship_name') }}" required>
        </div>

        <div class="admin-form-row">
            <label>Phone</label>
            <input type="text" name="ship_phone" value="{{ old('ship_phone') }}" required>
        </div>

        <div class="admin-form-row" style="grid-column:1 / -1;">
            <label>Address</label>
            <input type="text" name="ship_address" value="{{ old('ship_address') }}" required>
        </div>

        <div class="admin-form-row">
            <label>City</label>
            <input type="text" name="ship_city" value="{{ old('ship_city') }}" required>
        </div>

        <div class="admin-form-row">
            <label>State</label>
            <input type="text" name="ship_state" value="{{ old('ship_state') }}" required>
        </div>

        <div class="admin-form-row">
            <label>Pincode</label>
            <input type="text" name="ship_pincode" value="{{ old('ship_pincode') }}" required>
        </div>

        <div class="admin-form-row">
            <label>Tracking Number (optional)</label>
            <input type="text" name="tracking_number" value="{{ old('tracking_number') }}">
        </div>

        <div class="admin-form-row" style="grid-column:1 / -1;">
            <label>Notes</label>
            <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
        </div>

        <div class="admin-form-row" style="grid-column:1 / -1;">
            <h4 style="margin:8px 0;">Order Items</h4>
            <p class="admin-muted" style="margin:0 0 8px;">Add one or more line items. Unit price is optional and defaults to product price.</p>
        </div>

        <div id="order-items" style="grid-column:1 / -1; display:grid; gap:8px;">
            <div class="admin-grid order-item-row" style="grid-template-columns:2fr 1fr 1fr auto; gap:8px;">
                <select name="items[0][product_id]" required>
                    <option value="">Select product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="items[0][quantity]" min="1" value="1" required>
                <input type="number" name="items[0][unit_price]" min="0" step="0.01" placeholder="Auto">
                <button type="button" class="admin-btn-secondary remove-item" disabled>Remove</button>
            </div>
        </div>

        <div style="grid-column:1 / -1; display:flex; gap:8px;">
            <button type="button" id="add-item" class="admin-btn-secondary">Add Item</button>
            <button type="submit" class="admin-btn">Create Order</button>
        </div>
    </form>
</section>
@endsection

@section('scripts')
<script>
    (function() {
        const container = document.getElementById('order-items');
        const addButton = document.getElementById('add-item');
        let itemIndex = 1;

        function updateRemoveButtons() {
            const rows = container.querySelectorAll('.order-item-row');
            rows.forEach((row, idx) => {
                const btn = row.querySelector('.remove-item');
                btn.disabled = rows.length === 1;
            });
        }

        addButton.addEventListener('click', function() {
            const firstRow = container.querySelector('.order-item-row');
            const row = firstRow.cloneNode(true);

            row.querySelectorAll('select, input').forEach((field) => {
                const name = field.getAttribute('name');
                field.setAttribute('name', name.replace(/items\[\d+\]/, `items[${itemIndex}]`));
                if (field.tagName === 'SELECT') {
                    field.value = '';
                } else if (field.name.endsWith('[quantity]')) {
                    field.value = '1';
                } else {
                    field.value = '';
                }
            });

            row.querySelector('.remove-item').disabled = false;
            container.appendChild(row);
            itemIndex += 1;
            updateRemoveButtons();
        });

        container.addEventListener('click', function(e) {
            if (!e.target.classList.contains('remove-item')) return;
            const row = e.target.closest('.order-item-row');
            if (!row) return;
            row.remove();
            updateRemoveButtons();
        });

        updateRemoveButtons();
    })();
</script>
@endsection