@extends('admin.layouts.app')

@section('title', 'Create Coupon')

@section('content')
<section class="admin-panel">
    <h3>Create Coupon</h3>
    @include('admin.coupons.partials.form', [
        'action' => route('admin.coupons.store'),
        'method' => 'POST',
        'coupon' => null,
    ])
</section>
@endsection
