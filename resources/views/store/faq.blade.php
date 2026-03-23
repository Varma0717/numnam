@extends('store.layouts.app')

@section('title', 'NumNam - FAQ')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">FAQ</span>
        <h1>Quick answers for parents</h1>
        <p>Everything from age suitability to subscriptions, payment, and delivery.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="store-grid two">
        @foreach($faqs as $faq)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $faq['q'] }}</h4>
                    <p class="meta">{{ $faq['a'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
