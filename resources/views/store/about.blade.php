@extends('store.layouts.app')

@section('title', 'NumNam - About Us')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Our Story</span>
        <h1>From clinical care to everyday baby nutrition</h1>
        <p>NumNam was built by parents and doctors to make clean-label baby food easier, safer, and more practical for busy families.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Why NumNam Exists</h3></div></div>
    <p class="meta">Our founders experienced how hard it is to find truly transparent baby food options with balanced taste and nutrition. NumNam combines pediatric thinking, practical ingredients, and age-aware formats so feeding becomes a joyful routine instead of a daily struggle.</p>
</section>

<section class="section">
    <div class="section-head"><div><h3>Meet The Founders</h3></div></div>
    <div class="store-grid three">
        @foreach($founders as $founder)
            <article class="card">
                <div class="card-body">
                    <h4>{{ $founder['name'] }}</h4>
                    <p class="meta"><strong>{{ $founder['role'] }}</strong></p>
                    <p class="meta">{{ $founder['bio'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
