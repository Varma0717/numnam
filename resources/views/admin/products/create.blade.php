@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Add Product</h2>
        <p class="admin-desc">Create a new product for your store.</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Products</a>
</div>

<form method="POST" action="{{ route('admin.products.store') }}">
    @csrf
    @include('admin.products.partials.form')
</form>
@endsection