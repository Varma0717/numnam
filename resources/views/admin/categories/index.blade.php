@extends('admin.layouts.app')

@section('title', 'Categories - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Categories</h2>
    <p class="admin-desc">Manage product categories</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td><strong>{{ $category->name }}</strong></td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->products_count ?? $category->products()->count() }}</td>
                <td>{{ $category->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No categories found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $categories->links() }}</div>
</section>
@endsection
