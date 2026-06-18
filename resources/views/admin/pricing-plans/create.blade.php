@extends('admin.layouts.app')

@section('title', 'Add Subscription Plan')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Add Subscription Plan</h2>
        <p class="admin-desc">Create a new subscription plan for mobile and storefront.</p>
    </div>
    <a href="{{ route('admin.pricing-plans.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Plans</a>
</div>

<form method="POST" action="{{ route('admin.pricing-plans.store') }}">
    @csrf
    @include('admin.pricing-plans.partials.form')
</form>
@endsection
