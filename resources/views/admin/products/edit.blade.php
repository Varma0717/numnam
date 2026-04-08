@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Edit Product: {{ $product->name }}</h2>
        <p class="admin-desc">Last updated {{ $product->updated_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Products</a>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf
    @method('PUT')
    @include('admin.products.partials.form')
</form>
@endsection