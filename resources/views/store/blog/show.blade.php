@extends('store.layouts.app')

@section('title', 'NumNam - ' . $blog->title)
@section('meta_description', Str::limit($blog->excerpt, 160))
@section('og_image', $blog->featured_image ?: asset('assets/images/background_img.jpg'))

@section('content')
{{-- Hero with image background --}}
<section class="section pb-6 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-900/10">
        <div class="absolute inset-0">
            <img class="h-full w-full object-cover" src="{{ $blog->featured_image ?: asset('assets/images/background_img.jpg') }}" alt="{{ $blog->title }}">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/70 via-slate-900/40 to-slate-900/20"></div>
        </div>
        <div class="relative px-6 py-14 sm:px-10 lg:px-14 lg:py-20">
            <span class="inline-flex rounded-full border border-white/30 bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-white backdrop-blur-sm">{{ $blog->category?->name ?? 'Article' }}</span>
            <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">{{ $blog->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-white/80">
                <span>{{ optional($blog->published_at)->format('d M Y') }}</span>
                <span aria-hidden="true">&middot;</span>
                <span>{{ number_format($blog->view_count) }} views</span>
                @if($blog->author)
                <span aria-hidden="true">&middot;</span>
                <span>By {{ $blog->author->name }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Article Content --}}
<section class="section pb-8">
    <div class="mx-auto w-full">
        <div class="rounded-3xl border border-slate-200 bg-white px-7 py-8 shadow-sm sm:px-10 sm:py-10 lg:px-12 lg:py-12">
            <div class="prose prose-slate max-w-none prose-headings:font-extrabold prose-headings:tracking-tight prose-a:text-numnam-700 prose-img:rounded-2xl">
                {!! $blog->content !!}
            </div>
        </div>
        <div class="mt-6">
            <a class="inline-flex h-10 items-center gap-2 rounded-full border border-slate-200 bg-white px-5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50" href="{{ route('store.blog.index') }}">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Back to Blog
            </a>
        </div>
    </div>
</section>
@endsection