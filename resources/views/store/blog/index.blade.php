@extends('store.layouts.app')

@section('title', 'NumNam - Blog')
@section('meta_description', 'Parenting tips, nutrition guides, and feeding advice from the NumNam team.')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Parent Guides</span>
        <h1>Blog & Learning Center</h1>
        <p>Practical feeding guides, nutrition breakdowns, and parenting tips for every stage.</p>
    </div>
</section>

<section class="section">
    <div class="store-grid three">
        @forelse($blogs as $blog)
        <article class="card hover-up fade-in-up">
            <div class="media" style="background-image:url('{{ $blog->featured_image ?: asset('assets/images/background_img.jpg') }}')">
                @if($blog->category)
                <span class="badge-new">{{ $blog->category->name }}</span>
                @endif
            </div>
            <div class="card-body">
                <p class="meta blog-date">{{ optional($blog->published_at)->format('d M Y') }}</p>
                <h4><a href="{{ route('store.blog.show', $blog) }}">{{ $blog->title }}</a></h4>
                <p class="meta">{{ \Illuminate\Support\Str::limit($blog->excerpt, 130) }}</p>
                <a class="blog-read-more" href="{{ route('store.blog.show', $blog) }}">Read More &rarr;</a>
            </div>
        </article>
        @empty
        <div class="empty-state">
            <p>No published blog posts yet.</p>
            <a class="cta-btn" href="{{ route('store.home') }}">Back to Home</a>
        </div>
        @endforelse
    </div>

    <div class="pager">{{ $blogs->links() }}</div>
</section>
@endsection