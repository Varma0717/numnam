@extends('admin.layouts.app')

@section('title', 'Add Blog Post - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Add Blog Post</h2>
        <p class="admin-desc">Create a new blog post</p>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Posts</a>
</div>

<form method="POST" action="{{ route('admin.blogs.store') }}">
    @csrf
    @include('admin.blogs.partials.form')
</form>
@endsection