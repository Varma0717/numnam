@extends('store.layouts.app')

@section('title', '500 - Server Error')

@section('content')
<section class="section error-page">
    <div class="error-page-code">500</div>
    <h1>Something Went Wrong</h1>
    <p class="meta error-page-message">We're having some technical difficulties. Please try again in a few minutes.</p>
    <a class="cta-btn" href="{{ route('store.home') }}">Go Home</a>
</section>
@endsection