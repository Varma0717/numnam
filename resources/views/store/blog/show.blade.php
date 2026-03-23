@extends('store.layouts.app')

@section('title', 'NumNam - ' . $blog->title)

@section('content')
<section class="hero section in-view">
    <div>
        <span class="kicker">Article</span>
        <h1>{{ $blog->title }}</h1>
        <p>{{ optional($blog->published_at)->format('d M Y') }} | {{ number_format($blog->view_count) }} views</p>
    </div>
    <div class="hero-art" style="background-image:url('{{ $blog->featured_image ?: asset('assets/images/background_img.jpg') }}'); background-size:cover;"></div>
</section>

<section class="section">
    <article class="meta" style="font-size:1rem; line-height:1.7; color:#24303a;">
        {!! $blog->content !!}
    </article>
</section>
@endsection
