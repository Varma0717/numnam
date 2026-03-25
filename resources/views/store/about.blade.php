@extends('store.layouts.app')

@section('title', 'NumNam - About Us')
@section('meta_description', 'Learn how NumNam was built by parents and doctors to make clean-label baby food safer and more practical for busy families.')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Our Story</span>
        <h1>From clinical care to everyday baby nutrition</h1>
        <p>NumNam was built by parents and doctors to make clean-label baby food easier, safer, and more practical for busy families.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section fade-in-up">
    <div class="about-mission-grid">
        <div>
            <div class="section-head">
                <div>
                    <h3>Why NumNam Exists</h3>
                </div>
            </div>
            <p class="about-text">Our founders experienced how hard it is to find truly transparent baby food options with balanced taste and nutrition. NumNam combines pediatric thinking, practical ingredients, and age-aware formats so feeding becomes a joyful routine instead of a daily struggle.</p>
            <p class="about-text">Every product is developed with clinical nutrition input, sourced with clean-label standards, and designed for real mealtime scenarios — from first tastes to self-feeding toddlers.</p>
        </div>
        <div class="about-values">
            <div class="about-value-card">
                <span class="about-value-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                    </svg>
                </span>
                <div>
                    <h4>Safety First</h4>
                    <p class="meta">Every batch tested for purity and age-appropriate composition.</p>
                </div>
            </div>
            <div class="about-value-card">
                <span class="about-value-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 21c6 0 12-6 12-12V3h-6C6 3 3 6 3 12s3 9 3 9z" />
                    </svg>
                </span>
                <div>
                    <h4>Clean Ingredients</h4>
                    <p class="meta">No preservatives, no refined sugar, no artificial flavors.</p>
                </div>
            </div>
            <div class="about-value-card">
                <span class="about-value-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M8 14s1.5 2 4 2 4-2 4-2" />
                        <line x1="9" y1="9" x2="9.01" y2="9" />
                        <line x1="15" y1="9" x2="15.01" y2="9" />
                    </svg>
                </span>
                <div>
                    <h4>Parent-Friendly</h4>
                    <p class="meta">Designed for real families, busy mornings, and fuss-free feeding.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section fade-in-up">
    <div class="section-head">
        <div>
            <h3>Meet The Founders</h3>
        </div>
    </div>
    <div class="store-grid three">
        @foreach($founders as $founder)
        <article class="card founder-card hover-up">
            <div class="founder-avatar">{{ strtoupper(substr($founder['name'], 0, 1) . substr(strstr($founder['name'], ' '), 1, 1)) }}</div>
            <div class="card-body founder-body">
                <h4>{{ $founder['name'] }}</h4>
                <span class="chip">{{ $founder['role'] }}</span>
                <p class="meta founder-bio">{{ $founder['bio'] }}</p>
            </div>
        </article>
        @endforeach
    </div>
</section>

<section class="section fade-in-up about-cta-section">
    <h3>Ready to try NumNam?</h3>
    <p class="meta">Discover stage-wise nutrition made with ingredients you can trust.</p>
    <div class="hero-actions about-cta-actions">
        <a class="cta-btn" href="{{ route('store.products') }}">Shop Products</a>
        <a class="btn-soft" href="{{ route('store.contact') }}">Get in Touch</a>
    </div>
</section>
@endsection