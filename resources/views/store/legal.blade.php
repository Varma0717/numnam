@extends('store.layouts.app')

@section('title', 'NumNam - ' . $page['title'])

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Policy</span>
        <h1>{{ $page['title'] }}</h1>
        <p>Please review this policy before placing orders or using platform features.</p>
    </div>
</section>

<section class="section">
    @foreach($page['sections'] as $section)
    <article class="legal-section">
        <h2>{{ $section['heading'] }}</h2>
        <p class="meta">{{ $section['text'] }}</p>
    </article>
    @endforeach
</section>
@endsection