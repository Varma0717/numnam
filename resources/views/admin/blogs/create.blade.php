@extends('admin.layouts.app')

@section('title', 'Add Blog Post - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Add Blog Post</h2>
    <p class="admin-desc">Create a new blog post</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.blogs.store') }}" style="padding:16px;">
        @csrf
        @include('admin.blogs.partials.form')
    </form>
</section>
@endsection