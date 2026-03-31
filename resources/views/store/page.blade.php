@extends('store.layouts.app')

@section('title', 'NumNam - ' . ($page->meta_title ?: $page->title))
@section('meta_description', $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags((string) optional($sections->first())->content), 160))

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/90 bg-gradient-to-br from-[#fffaf4] via-white to-[#fff3e6] px-6 py-10 sm:px-10 lg:px-12">
        <div class="pointer-events-none absolute -left-16 -top-20 h-56 w-56 rounded-full bg-numnam-200/45 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-16 h-56 w-56 rounded-full bg-orange-100/65 blur-3xl"></div>
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Page</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $page->title }}</h1>
        </div>
    </div>
</section>

<section class="section pb-12">
    <div class="mx-auto max-w-3xl space-y-6">
        @forelse($sections as $section)
        <article class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
            @if($section->title)
            <h2 class="text-lg font-bold text-slate-900">{{ $section->title }}</h2>
            @endif

            @if($section->content)
            <div class="mt-3 text-sm leading-relaxed text-slate-600">{!! nl2br(e($section->content)) !!}</div>
            @endif

            @if(!empty($section->data))
            <pre class="mt-3 overflow-x-auto rounded-xl bg-slate-50 p-4 text-xs text-slate-600">{{ json_encode($section->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @endif
        </article>
        @empty
        <article class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
            <p class="text-sm text-slate-500">This page is published, but no active sections are available yet.</p>
        </article>
        @endforelse
    </div>
</section>
@endsection