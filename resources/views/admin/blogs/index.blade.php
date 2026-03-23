@extends('admin.layouts.app')

@section('title', 'Blog Posts - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Blog Posts</h2>
    <p class="admin-desc">Manage blog content</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Published</th>
        </tr>
        </thead>
        <tbody>
        @forelse($blogs as $blog)
            <tr>
                <td>{{ $blog->id }}</td>
                <td><strong>{{ $blog->title }}</strong></td>
                <td>{{ $blog->slug }}</td>
                <td>{{ ucfirst($blog->status ?? 'draft') }}</td>
                <td>{{ $blog->published_at ? $blog->published_at->format('d M Y') : '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No blog posts found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $blogs->links() }}</div>
</section>
@endsection
