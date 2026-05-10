@extends('admin.layouts.app')

@section('title', 'Blog Categories - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Blog Categories</h2>
        <p class="admin-desc">{{ $categories->total() }} blog categories</p>
    </div>
    <a href="{{ route('admin.blog-categories.create') }}" class="admin-btn" style="text-decoration:none;">Add Category</a>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search blog categories...">
        <button class="admin-btn" type="submit">Search</button>
        @if(request('q'))
        <a href="{{ route('admin.blog-categories.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Parent</th>
                    <th>Posts</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td><code style="font-size:12px; background:#f0f0f1; padding:2px 6px; border-radius:3px;">{{ $category->slug }}</code></td>
                    <td>{{ $category->parent?->name ?? '—' }}</td>
                    <td>{{ $category->blogs_count }}</td>
                    <td>{{ $category->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="admin-link">Edit</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.blog-categories.destroy', $category) }}" style="display:inline;" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="admin-empty">No blog categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $categories->links() }}</div>
</section>
@endsection