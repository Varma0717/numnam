@extends('admin.layouts.app')

@section('title', 'Edit Coupon')

@section('content')
<section class="admin-panel">
    <h3>Edit Coupon {{ $coupon->code }}</h3>
    @include('admin.coupons.partials.form', [
        'action' => route('admin.coupons.update', $coupon),
        'method' => 'PUT',
        'coupon' => $coupon,
    ])
</section>
@endsection
