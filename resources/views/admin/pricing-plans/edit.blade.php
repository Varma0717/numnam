@extends('admin.layouts.app')

@section('title', 'Edit Subscription Plan - ' . $pricingPlan->name)

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Edit Subscription Plan: {{ $pricingPlan->name }}</h2>
        <p class="admin-desc">Last updated {{ $pricingPlan->updated_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('admin.pricing-plans.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Plans</a>
</div>

<form method="POST" action="{{ route('admin.pricing-plans.update', $pricingPlan) }}">
    @csrf
    @method('PUT')
    @include('admin.pricing-plans.partials.form', ['plan' => $pricingPlan])
</form>
@endsection
