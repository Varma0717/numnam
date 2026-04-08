@extends('admin.layouts.app')

@section('title', 'Edit Category - ' . $category->name)

@section('content')
<div class="admin-page-header">
    <h2>Edit Category: {{ $category->name }}</h2>
    <p class="admin-desc">Update category details</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" style="padding:16px;">
        @csrf
        @method('PUT')
        @include('admin.categories.partials.form')
    </form>
</section>
@endsection