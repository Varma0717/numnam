@extends('admin.layouts.app')

@section('title', 'Add Category - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Add Category</h2>
    <p class="admin-desc">Create a new product category</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.categories.store') }}" style="padding:16px;">
        @csrf
        @include('admin.categories.partials.form')
    </form>
</section>
@endsection