@extends('store.layouts.app')

@section('title', 'NumNam - Recipes & Nutrition Tips')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Recipes</span>
        <h1>Infant nutrition recipes and practical feeding tips</h1>
        <p>Simple, stage-aware ideas that support weaning and healthy mealtime habits.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="section-head"><div><h3>Core Feeding Tips</h3></div></div>
    <ul>
        @foreach($recipeTips as $tip)
            <li class="meta" style="margin-bottom:.55rem;">{{ $tip }}</li>
        @endforeach
    </ul>
</section>

<section class="section">
    <div class="section-head"><div><h3>Carrot Puree Starter (6M+)</h3></div></div>
    <p class="meta"><strong>Ingredients:</strong> 1 carrot, filtered water, optional tiny drop of cold-pressed oil.</p>
    <ol>
        <li class="meta">Peel and chop carrot into small chunks.</li>
        <li class="meta">Steam until very soft.</li>
        <li class="meta">Blend with little water until smooth.</li>
        <li class="meta">Serve warm and fresh with spoon-friendly consistency.</li>
    </ol>
</section>

<section class="section">
    <div class="section-head"><div><h3>Latest From Blog</h3></div></div>
    <div class="store-grid three">
        @forelse($featuredArticles as $article)
            <article class="card">
                <div class="card-body">
                    <h4><a href="{{ route('store.blog.show', $article) }}">{{ $article->title }}</a></h4>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($article->excerpt, 110) }}</p>
                </div>
            </article>
        @empty
            <p class="meta">No recipe articles yet.</p>
        @endforelse
    </div>
</section>
@endsection
