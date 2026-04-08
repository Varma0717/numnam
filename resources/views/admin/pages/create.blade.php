@extends('admin.layouts.app')

@section('title', 'Add Page - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Add Page</h2>
        <p class="admin-desc">Create a new page for your site.</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Pages</a>
</div>

<form method="POST" action="{{ route('admin.pages.store') }}">
    @csrf
    @include('admin.pages.partials.form')
</form>
@endsection