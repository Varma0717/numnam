@extends('store.layouts.app')

@section('title', 'NumNam - ' . ($page->meta_title ?: $page->title))
@section('meta_description', $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags((string) optional($sections->first())->content), 160))

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Page</span>
        <h1>{{ $page->title }}</h1>
    </div>
</section>

<section class="section">
    @forelse($sections as $section)
    <article class="legal-section">
        @if($section->title)
        <h3>{{ $section->title }}</h3>
        @endif

        @if($section->content)
        <p class="meta">{!! nl2br(e($section->content)) !!}</p>
        @endif

        @if(!empty($section->data))
        <pre class="meta" style="white-space: pre-wrap;">{{ json_encode($section->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        @endif
    </article>
    @empty
    <article class="legal-section">
        <p class="meta">This page is published, but no active sections are available yet.</p>
    </article>
    @endforelse
</section>
@endsection