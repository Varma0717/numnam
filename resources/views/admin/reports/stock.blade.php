@extends('admin.layouts.app')

@section('title', 'Stock Report - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Stock Report</h2>
    <p class="admin-desc">Inventory levels and product stock status.</p>
</div>

<div class="admin-grid metrics-grid" style="margin-bottom:20px;">
    <div class="metric-card">
        <strong>{{ $totalProducts }}</strong>
        <span>Active Products</span>
    </div>
    <div class="metric-card" style="border-left-color:#dba617;">
        <strong>{{ $lowStock->count() }}</strong>
        <span>Low Stock (≤10)</span>
    </div>
    <div class="metric-card" style="border-left-color:#d63638;">
        <strong>{{ $outOfStock }}</strong>
        <span>Out of Stock</span>
    </div>
</div>

<section class="admin-panel">
    <h3 style="padding:8px 12px; margin:0; border-bottom:1px solid var(--wp-border);">Low Stock Products</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStock as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->sku ?? '—' }}</td>
                    <td>
                        @if($product->stock_quantity <= 0)
                            <span class="admin-badge admin-badge-danger">Out of stock</span>
                            @elseif($product->stock_quantity <= 5)
                                <span class="admin-badge admin-badge-warning">{{ $product->stock_quantity }}</span>
                                @else
                                <span class="admin-badge admin-badge-info">{{ $product->stock_quantity }}</span>
                                @endif
                    </td>
                    <td>₹{{ number_format($product->price, 0) }}</td>
                    <td><a href="{{ route('admin.products.edit', $product->id) }}" class="admin-row-action">Edit</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="admin-empty">All products are well stocked!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection