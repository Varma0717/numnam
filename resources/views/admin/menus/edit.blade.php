@extends('admin.layouts.app')

@section('title', 'Edit Menu - ' . $menu->name)

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Edit Menu: {{ $menu->name }}</h2>
        <p class="admin-desc">Manage items in this navigation menu.</p>
    </div>
    <a href="{{ route('admin.menus.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Menus</a>
</div>

<form method="POST" action="{{ route('admin.menus.update', $menu) }}">
    @csrf
    @method('PUT')
    @include('admin.menus.partials.form')
</form>
@endsection