@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)

@section('content')
<section class="admin-panel">
    <h3>Edit Product: {{ $product->name }}</h3>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="admin-grid" style="gap:.6rem;">
        @csrf
        @method('PUT')
        @include('admin.products.partials.form')
    </form>
</section>
@endsection
