@extends('admin.layouts.app')

@section('title', 'Pages - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Pages</h2>
        <p class="admin-desc">{{ $pages->total() }} pages</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="admin-btn" style="text-decoration:none;">Add Page</a>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search pages...">
        <button class="admin-btn" type="submit">Search</button>
        @if(request('q'))
        <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Template</th>
                    <th>Sections</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td><strong>{{ $page->title }}</strong></td>
                    <td><code style="font-size:12px; background:#f0f0f1; padding:2px 6px; border-radius:3px;">/{{ $page->slug }}</code></td>
                    <td>{{ $page->template ?: 'default' }}</td>
                    <td>{{ $page->sections_count }}</td>
                    <td>
                        <span class="status-badge status-badge--{{ $page->status === 'published' ? 'active' : 'pending' }}">
                            {{ ucfirst($page->status) }}
                        </span>
                    </td>
                    <td>{{ $page->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.pages.edit', $page) }}" class="admin-link">Edit</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" style="display:inline;" onsubmit="return confirm('Delete this page?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="admin-empty">No pages found. <a href="{{ route('admin.pages.create') }}" class="admin-link">Create one</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $pages->links() }}</div>
</section>
@endsection