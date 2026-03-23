@extends('admin.layouts.app')

@section('title', 'Manage Products')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:.6rem; flex-wrap:wrap;">
        <h3 style="margin:0;">Products</h3>
        <a href="{{ route('admin.products.create') }}" class="admin-btn" style="text-decoration:none;">Add Product</a>
    </div>

    <form method="GET" style="display:grid; grid-template-columns:1fr auto; gap:.5rem; margin:.7rem 0;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name or slug">
        <button class="admin-btn" type="submit">Search</button>
    </form>

    <table class="admin-table">
        <thead>
        <tr>
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
                <td>{{ $product->name }}</td>
                <td>{{ $product->category?->name ?: '-' }}</td>
                <td>Rs {{ number_format($product->sale_price ?: $product->price, 0) }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->is_active ? 'Active' : 'Draft' }}</td>
                <td>
                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-link">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-link" style="border:0; background:none; cursor:pointer; color:#c33;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No products found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top:.75rem;">{{ $products->links() }}</div>
</section>
@endsection
