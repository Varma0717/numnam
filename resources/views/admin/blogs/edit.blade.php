@extends('admin.layouts.app')

@section('title', 'Edit Blog Post - ' . $blog->title)

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Edit Post: {{ $blog->title }}</h2>
        <p class="admin-desc">Last updated {{ $blog->updated_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Posts</a>
</div>

<form method="POST" action="{{ route('admin.blogs.update', $blog) }}">
    @csrf
    @method('PUT')
    @include('admin.blogs.partials.form')
</form>
@endsection