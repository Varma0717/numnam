@extends('admin.layouts.app')

@section('title', 'Add Blog Category - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Add Blog Category</h2>
    <p class="admin-desc">Create a new blog category</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.blog-categories.store') }}" style="padding:16px;">
        @csrf
        @include('admin.blog-categories.partials.form')
    </form>
</section>
@endsection