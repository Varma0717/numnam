@extends('admin.layouts.app')

@section('title', 'Manage Products')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:.6rem; flex-wrap:wrap;">
        <h3 style="margin:0;">Products</h3>
        <a href="{{ route('admin.products.create') }}" class="admin-btn" style="text-decoration:none;">Add Product</a>
    </div>

    <form method="GET" class="admin-search-bar" style="padding:10px 0;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name or slug">
        <button class="admin-btn" type="submit">Search</button>
        @if(request('q'))
        <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <form method="POST" action="{{ Route::has('admin.products.bulk') ? route('admin.products.bulk') : '#' }}" id="bulk-form">
        @csrf
        <div class="admin-bulk-bar">
            <select name="bulk_action">
                <option value="">Bulk Actions</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
            </select>
            <button class="admin-btn" type="submit" onclick="return confirm('Apply bulk action to selected items?')">Apply</button>
            <span class="admin-muted" id="bulk-count"></span>
        </div>
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th class="check-column"><input type="checkbox" id="select-all"></th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="check-column"><input type="checkbox" class="row-check" name="ids[]" value="{{ $product->id }}" form="bulk-form"></td>
                <td><strong>{{ $product->name }}</strong></td>
                <td>{{ $product->category?->name ?: '-' }}</td>
                <td>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <span class="status-badge status-badge--{{ $product->is_active ? 'active' : 'pending' }}">
                        {{ $product->is_active ? 'Active' : 'Draft' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-link">Edit</a>
                    <span style="color:var(--wp-border); margin:0 4px;">|</span>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="admin-empty">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:.75rem;">{{ $products->links() }}</div>
</section>
@endsection