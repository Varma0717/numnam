@extends('admin.layouts.app')

@section('title', 'Edit Blog Category - ' . $blogCategory->name)

@section('content')
<div class="admin-page-header">
    <h2>Edit Blog Category: {{ $blogCategory->name }}</h2>
    <p class="admin-desc">Update blog category details</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.blog-categories.update', $blogCategory) }}" style="padding:16px;">
        @csrf
        @method('PUT')
        @include('admin.blog-categories.partials.form')
    </form>
</section>
@endsection