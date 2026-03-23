@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<section class="admin-panel">
    <h3>Add Product</h3>
    <form method="POST" action="{{ route('admin.products.store') }}" class="admin-grid" style="gap:.6rem;">
        @csrf
        @include('admin.products.partials.form')
    </form>
</section>
@endsection
