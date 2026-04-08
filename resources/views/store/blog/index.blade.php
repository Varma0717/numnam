@extends('store.layouts.app')

@section('title', 'NumNam - Blog')
@section('meta_description', 'Parenting tips, nutrition guides, and feeding advice from the NumNam team.')

@section('content')
<section class="section pb-8 pt-4 sm:pt-8">
    <div class="relative overflow-hidden rounded-[2rem] border-3 bg-[#FFF0F5] px-6 py-10 sm:px-10 lg:px-12" style="border-color:#FFD6E5;">
        <div class="relative max-w-3xl">
            <span class="inline-flex w-fit rounded-full border border-numnam-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Parent Guides</span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Blog & Learning Center</h1>
            <p class="mt-3 max-w-2xl text-base leading-relaxed text-slate-600">Practical feeding guides, nutrition breakdowns, and parenting tips for every stage.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse($blogs as $blog)
        <article class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
            <a class="block aspect-[4/3] overflow-hidden" href="{{ route('store.blog.show', $blog) }}">
                <img class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    src="{{ $blog->featured_image ?: asset('assets/images/background_img.jpg') }}"
                    alt="{{ $blog->title }}" loading="lazy">
            </a>
            @if($blog->category)
            <div class="px-5 pt-4">
                <span class="inline-flex rounded-full border border-numnam-200 bg-numnam-50 px-2.5 py-0.5 text-xs font-semibold text-numnam-700">{{ $blog->category->name }}</span>
            </div>
            @endif
            <div class="px-5 pb-6 pt-3">
                <p class="text-xs text-slate-400">{{ optional($blog->published_at)->format('d M Y') }}</p>
                <h4 class="mt-1 text-base font-bold leading-snug text-slate-900">
                    <a class="hover:text-numnam-700" href="{{ route('store.blog.show', $blog) }}">{{ $blog->title }}</a>
                </h4>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">{{ \Illuminate\Support\Str::limit($blog->excerpt, 130) }}</p>
                <a class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-numnam-700 hover:underline" href="{{ route('store.blog.show', $blog) }}">Read More &rarr;</a>
            </div>
        </article>
        @empty
        <div class="col-span-full rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-slate-500">No published blog posts yet.</p>
            <a class="mt-4 inline-flex h-10 items-center rounded-full bg-numnam-600 px-6 text-sm font-semibold text-white hover:bg-numnam-700" href="{{ route('store.home') }}">Back to Home</a>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $blogs->links() }}</div>
</section>
@endsection