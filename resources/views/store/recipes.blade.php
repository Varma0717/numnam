@extends('store.layouts.app')

@section('title', 'NumNam - Recipes & Nutrition Tips')
@section('meta_description', 'Simple, stage-aware recipes and feeding tips for infants and toddlers.')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Recipes</span>
        <h1>Infant nutrition recipes and practical feeding tips</h1>
        <p>Simple, stage-aware ideas that support weaning and healthy mealtime habits.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>Core Feeding Tips</h3>
        </div>
    </div>
    <div class="store-grid two">
        @foreach($recipeTips as $index => $tip)
        <article class="card tip-card">
            <div class="card-body">
                <div class="tip-number">{{ $index + 1 }}</div>
                <p>{{ $tip }}</p>
            </div>
        </article>
        @endforeach
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>Carrot Puree Starter (6M+)</h3>
        </div>
    </div>
    <div class="recipe-card">
        <div class="recipe-header">
            <span class="kicker">Beginner Recipe</span>
            <div class="recipe-meta-row">
                <span class="recipe-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    15 mins
                </span>
                <span class="recipe-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l1.9 4.9L19 9l-5.1 2.1L12 16l-1.9-4.9L5 9l5.1-2.1L12 2z" />
                    </svg>
                    Easy
                </span>
                <span class="recipe-meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    6M+
                </span>
            </div>
        </div>
        <div class="recipe-body">
            <div>
                <h4>Ingredients</h4>
                <ul class="recipe-ingredients">
                    <li>1 medium carrot</li>
                    <li>Filtered water</li>
                    <li>Tiny drop of cold-pressed oil (optional)</li>
                </ul>
            </div>
            <div>
                <h4>Method</h4>
                <ol class="recipe-steps">
                    <li>Peel and chop carrot into small chunks.</li>
                    <li>Steam until very soft (about 10 minutes).</li>
                    <li>Blend with a little water until smooth.</li>
                    <li>Serve warm and fresh — spoon-friendly consistency.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>Latest From Blog</h3>
        </div>
    </div>
    <div class="store-grid three">
        @forelse($featuredArticles as $article)
        <article class="card hover-up fade-in-up">
            <div class="media" style="background-image:url('{{ $article->featured_image ?: asset('assets/images/background_img.jpg') }}')">
                @if($article->category)
                <span class="badge-new">{{ $article->category->name }}</span>
                @endif
            </div>
            <div class="card-body">
                <p class="meta blog-date">{{ optional($article->published_at)->format('d M Y') }}</p>
                <h4><a href="{{ route('store.blog.show', $article) }}">{{ $article->title }}</a></h4>
                <p class="meta">{{ \Illuminate\Support\Str::limit($article->excerpt, 110) }}</p>
                <a class="blog-read-more" href="{{ route('store.blog.show', $article) }}">Read More &rarr;</a>
            </div>
        </article>
        @empty
        <div class="empty-state">
            <p>No recipe articles yet.</p>
        </div>
        @endforelse
    </div>

    @if($featuredArticles->isNotEmpty())
    <div class="section-footer">
        <a class="btn-soft" href="{{ route('store.blog.index') }}">View All Articles &rarr;</a>
    </div>
    @endif
</section>
@endsection