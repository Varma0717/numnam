@extends('store.layouts.app')

@section('title', 'NumNam - Blog')

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Parent guides</span>
        <h1>Blog and learning center</h1>
        <p>Dynamic content feed from the CMS blog module for education-led commerce growth.</p>
    </div>
    <div class="hero-art"></div>
</section>

<section class="section">
    <div class="store-grid three">
        @forelse($blogs as $blog)
            <article class="card">
                <div class="card-body">
                    <h4><a href="{{ route('store.blog.show', $blog) }}">{{ $blog->title }}</a></h4>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($blog->excerpt, 130) }}</p>
                    <p class="meta">{{ optional($blog->published_at)->format('d M Y') }}</p>
                </div>
            </article>
        @empty
            <p class="meta">No published blog posts yet.</p>
        @endforelse
    </div>

    <div class="pager">{{ $blogs->links() }}</div>
</section>
@endsection
