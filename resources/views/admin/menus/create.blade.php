@extends('admin.layouts.app')

@section('title', 'Add Menu - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Add Menu</h2>
        <p class="admin-desc">Create a navigation menu.</p>
    </div>
    <a href="{{ route('admin.menus.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Menus</a>
</div>

<form method="POST" action="{{ route('admin.menus.store') }}">
    @csrf
    @include('admin.menus.partials.form')
</form>
@endsection