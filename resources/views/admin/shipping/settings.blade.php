@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@section('content')
<section class="admin-panel">
    <h3>⚙️ Shipping Configuration</h3>
    <p class="meta">Manage shipping rates, thresholds, and product exclusions</p>

    <form method="POST" action="{{ route('admin.shipping.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <!-- Enable/Disable -->
        <fieldset>
            <legend>Shipping Status</legend>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enabled" value="1" {{ old('enabled', $settings->enabled) ? 'checked' : '' }}>
                    <strong>Enable Shipping Charges</strong>
                </label>
                <small>Uncheck to disable shipping fees (offer free shipping)</small>
            </div>
        </fieldset>

        <!-- Shipping Type -->
        <fieldset>
            <legend>Shipping Type</legend>
            <div class="form-group">
                <label for="type">Calculate Shipping As:</label>
                <select id="type" name="type" required>
                    <option value="flat_rate" {{ old('type', $settings->type) === 'flat_rate' ? 'selected' : '' }}>
                        Flat Rate (Fixed amount for all orders)
                    </option>
                    <option value="weight_based" disabled>
                        Weight-based (Coming soon)
                    </option>
                    <option value="zone_based" disabled>
                        Zone-based (Coming soon)
                    </option>
                </select>
                <small>Select how shipping costs should be calculated</small>
            </div>
        </fieldset>

        <!-- Flat Rate Settings -->
        <fieldset>
            <legend>Flat Rate Configuration</legend>
            <div class="admin-grid">
                <div class="form-group">
                    <label for="flat_rate_amount">Shipping Cost (Rs)</label>
                    <input 
                        type="number" 
                        id="flat_rate_amount" 
                        name="flat_rate_amount" 
                        min="0" 
                        step="0.01"
                        placeholder="99.00"
                        value="{{ old('flat_rate_amount', $settings->flat_rate_amount) }}"
                        required
                    >
                    <small>Amount to charge for shipping (e.g., 99 for Rs 99)</small>
                </div>

                <div class="form-group">
                    <label for="free_shipping_threshold">Free Shipping Above (Rs)</label>
                    <input 
                        type="number" 
                        id="free_shipping_threshold" 
                        name="free_shipping_threshold" 
                        min="0" 
                        step="0.01"
                        placeholder="500.00"
                        value="{{ old('free_shipping_threshold', $settings->free_shipping_threshold) }}"
                    >
                    <small>Orders above this amount get free shipping (leave blank for no threshold)</small>
                </div>
            </div>
        </fieldset>

        <!-- Notes -->
        <fieldset>
            <legend>Configuration Notes</legend>
            <div class="form-group">
                <label for="notes">Internal Notes</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    placeholder="e.g., Updated rates for monsoon season, COD extra charges..."
                    rows="3"
                >{{ old('notes', $settings->notes) }}</textarea>
                <small>For reference only (not shown to customers)</small>
            </div>
        </fieldset>

        <button type="submit" class="admin-btn">💾 Save Shipping Settings</button>
    </form>
</section>

<!-- Product Exclusions -->
<section class="admin-panel">
    <h3>📦 Products Excluded from Shipping</h3>
    <p class="meta">Products marked "Exclude from Shipping" won't incur shipping charges (e.g., kits, subscriptions)</p>

    @if($excludedProducts->count() > 0)
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Type</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($excludedProducts as $product)
            <tr>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                </td>
                <td>{{ $product->sku }}</td>
                <td><span class="badge">{{ ucfirst($product->type ?? 'product') }}</span></td>
                <td>Rs {{ number_format($product->price, 0) }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.product.toggle-shipping', $product) }}" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-soft btn-sm">Include in Shipping</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="meta" style="padding: 20px; text-align:center; color:#666;">No products are currently excluded from shipping</p>
    @endif
</section>

<!-- All Products with Toggle -->
<section class="admin-panel">
    <h3>🏷️ Manage Product Shipping Status</h3>
    <p class="meta">Toggle shipping exclusion for each product</p>

    <div class="admin-search">
        <input 
            type="text" 
            id="productSearch" 
            placeholder="Search products..." 
            class="input"
            style="max-width: 300px;"
        >
    </div>

    <table class="admin-table" id="productsTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Exclude from Shipping?</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allProducts as $product)
            <tr class="product-row" data-product="{{ strtolower($product->name) }}">
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>Rs {{ number_format($product->price, 0) }}</td>
                <td>
                    <span class="badge {{ $product->exclude_from_shipping ? 'badge-warning' : 'badge-success' }}">
                        {{ $product->exclude_from_shipping ? '✓ Excluded' : '✗ Included' }}
                    </span>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.product.toggle-shipping', $product) }}" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-soft btn-sm">
                            {{ $product->exclude_from_shipping ? 'Include' : 'Exclude' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</section>

<style>
    fieldset {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 16px;
        margin: 16px 0;
    }

    legend {
        font-weight: 600;
        padding: 0 8px;
        color: #333;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .btn-sm {
        padding: 8px 12px;
        font-size: 12px;
    }

    .admin-search {
        margin-bottom: 16px;
    }

    .product-row.hidden {
        display: none;
    }
</style>

<script>
document.getElementById('productSearch')?.addEventListener('keyup', function(e) {
    const query = e.target.value.toLowerCase();
    document.querySelectorAll('.product-row').forEach(row => {
        if (row.dataset.product.includes(query)) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    });
});
</script>
@endsection
