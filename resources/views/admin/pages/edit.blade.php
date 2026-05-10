@extends('admin.layouts.app')

@section('title', 'Edit Page - ' . $page->title)

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Edit Page: {{ $page->title }}</h2>
        <p class="admin-desc">Last updated {{ $page->updated_at->diffForHumans() }}</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Back to Pages</a>
</div>

<form method="POST" action="{{ route('admin.pages.update', $page) }}">
    @csrf
    @method('PUT')
    @include('admin.pages.partials.form')
</form>
@endsection