@extends('store.layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<section class="section error-page">
    <div class="error-page-code">404</div>
    <h1>Page Not Found</h1>
    <p class="meta error-page-message">Sorry, the page you're looking for doesn't exist or has been moved.</p>
    <div class="error-page-actions">
        <a class="cta-btn" href="{{ route('store.home') }}">Go Home</a>
        <a class="btn-soft" href="{{ route('store.products') }}">Shop Products</a>
    </div>
</section>
@endsection