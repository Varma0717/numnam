@php($data = $section->data)
<section style="background: #0f172a; color: #fff;">
    <div class="wrap">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <p>{{ $data['text'] ?? $section->content }}</p>
        <a class="btn btn-light" style="margin-top: 12px;" href="{{ $data['button_url'] ?? '#' }}">{{ $data['button_label'] ?? 'Get Started' }}</a>
    </div>
</section>
