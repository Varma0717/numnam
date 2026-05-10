@extends('store.layouts.app')

@section('title', 'NumNam - ' . $page['title'])

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Policy</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $page['title'] }}</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Please review this policy before placing orders or using platform features.</p>
        </div>
    </div>
</section>

<section class="section pb-12">
    <div class="mx-auto max-w-3xl space-y-6">
        @foreach($page['sections'] as $section)
        <article class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">{{ $section['heading'] }}</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $section['text'] }}</p>
        </article>
        @endforeach
    </div>
</section>
@endsection