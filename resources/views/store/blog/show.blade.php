@extends('store.layouts.app')

@section('title', 'NumNam - ' . $blog->title)
@section('meta_description', Str::limit($blog->excerpt, 160))
@section('og_image', $blog->featured_image ?: asset('assets/images/background_img.jpg'))

@section('content')
<section class="blog-hero section in-view">
    <div>
        <span class="kicker">{{ $blog->category?->name ?? 'Article' }}</span>
        <h1>{{ $blog->title }}</h1>
        <div class="blog-meta-row">
            <span>{{ optional($blog->published_at)->format('d M Y') }}</span>
            <span>&middot;</span>
            <span>{{ number_format($blog->view_count) }} views</span>
            @if($blog->author)
            <span>&middot;</span>
            <span>By {{ $blog->author->name }}</span>
            @endif
        </div>
    </div>
    <div class="hero-art" style="background-image:url('{{ $blog->featured_image ?: asset('assets/images/background_img.jpg') }}'); background-size:cover;"></div>
</section>

<section class="section blog-content fade-in-up">
    <article class="blog-article">
        {!! $blog->content !!}
    </article>
</section>

<section class="section fade-in-up blog-back">
    <a class="btn-soft" href="{{ route('store.blog.index') }}">&larr; Back to Blog</a>
</section>
@endsection