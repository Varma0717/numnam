@extends('admin.layouts.app')

@section('title', 'Categories - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Categories</h2>
        <p class="admin-desc">{{ $categories->total() }} categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="admin-btn" style="text-decoration:none;">Add Category</a>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search categories...">
        <button class="admin-btn" type="submit">Search</button>
        @if(request('q'))
        <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>
                        @if($category->image)
                        <img src="{{ $category->image }}" alt="" style="width:40px; height:40px; object-fit:cover; border-radius:4px; border:1px solid var(--wp-border);">
                        @else
                        <span style="display:inline-block; width:40px; height:40px; background:#f0f0f1; border-radius:4px; border:1px solid var(--wp-border);"></span>
                        @endif
                    </td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code style="font-size:12px; background:#f0f0f1; padding:2px 6px; border-radius:3px;">{{ $category->slug }}</code></td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <span class="status-badge status-badge--{{ $category->is_active ? 'active' : 'cancelled' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $category->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="admin-link">Edit</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="admin-empty">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $categories->links() }}</div>
</section>
@endsection