@extends('store.layouts.app')

@section('title', '403 - Forbidden')

@section('content')
<section class="section error-page">
    <div class="error-page-code">403</div>
    <h1>Access Denied</h1>
    <p class="meta" style="max-width:420px; margin:0 auto 24px;">You don't have permission to access this page.</p>
    <a class="cta-btn" href="{{ route('store.home') }}">Go Home</a>
</section>
@endsection
