@php($data = $section->data)
<section style="background: linear-gradient(135deg, #0f766e, #22c55e); color: #fff;">
    <div class="wrap">
        <h1>{{ $data['heading'] ?? $section->title }}</h1>
        <p style="max-width: 700px;">{{ $data['subheading'] ?? $section->content }}</p>
        <div style="display: flex; gap: 10px; margin-top: 16px; flex-wrap: wrap;">
            <a class="btn btn-light" href="{{ $data['primary_cta_url'] ?? '#' }}">{{ $data['primary_cta_label'] ?? 'Shop Now' }}</a>
            <a class="btn" style="background: rgba(255,255,255,0.2); color: #fff;" href="{{ $data['secondary_cta_url'] ?? '#' }}">{{ $data['secondary_cta_label'] ?? 'Learn More' }}</a>
        </div>
    </div>
</section>
