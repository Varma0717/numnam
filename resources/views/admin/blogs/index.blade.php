@extends('admin.layouts.app')

@section('title', 'Blog Posts - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Blog Posts</h2>
        <p class="admin-desc">{{ $blogs->total() }} posts</p>
    </div>
    <a href="{{ route('admin.blogs.create') }}" class="admin-btn" style="text-decoration:none;">Add Post</a>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search posts...">
        <select name="status" style="width:auto; min-width:120px; border:1px solid #8c8f94; border-radius:4px; padding:0 8px; font-size:14px; line-height:2; min-height:30px;">
            <option value="">All Status</option>
            <option value="draft" @selected(request('status')==='draft' )>Draft</option>
            <option value="published" @selected(request('status')==='published' )>Published</option>
        </select>
        <button class="admin-btn" type="submit">Filter</button>
        @if(request('q') || request('status'))
        <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <form method="POST" action="{{ route('admin.blogs.bulk') }}" id="bulk-form">
            @csrf
            <div class="admin-bulk-bar">
                <select name="bulk_action">
                    <option value="">Bulk Actions</option>
                    <option value="publish">Publish</option>
                    <option value="draft">Set Draft</option>
                    <option value="delete">Delete</option>
                </select>
                <button class="admin-btn" type="submit" onclick="return confirm('Apply bulk action to selected posts?')">Apply</button>
                <span class="admin-muted" id="bulk-count"></span>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all"></th>
                        <th style="width:50px;">ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                    <tr>
                        <td class="check-column"><input type="checkbox" name="ids[]" value="{{ $blog->id }}"></td>
                        <td>{{ $blog->id }}</td>
                        <td><strong>{{ $blog->title }}</strong></td>
                        <td>{{ $blog->category?->name ?? '—' }}</td>
                        <td>{{ $blog->author?->name ?? '—' }}</td>
                        <td>
                            <span class="status-badge status-badge--{{ $blog->status === 'published' ? 'active' : 'pending' }}">
                                {{ ucfirst($blog->status ?? 'draft') }}
                            </span>
                        </td>
                        <td>{{ $blog->published_at ? $blog->published_at->format('d M Y') : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="admin-link">Edit</a>
                            <span style="color:var(--wp-border); margin:0 4px;">|</span>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" style="display:inline;" onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="admin-empty">No blog posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>
    <div style="padding:12px;">{{ $blogs->links() }}</div>
</section>
@endsection