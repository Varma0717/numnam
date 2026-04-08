@extends('admin.layouts.app')

@section('title', 'Edit Blog Post - ' . $blog->title)

@section('content')
<div class="admin-page-header">
    <h2>Edit Post: {{ $blog->title }}</h2>
    <p class="admin-desc">Last updated {{ $blog->updated_at->format('d M Y H:i') }}</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" style="padding:16px;">
        @csrf
        @method('PUT')
        @include('admin.blogs.partials.form')
    </form>
</section>
@endsection