@php($data = $section->data)
<section>
    <div class="wrap">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <div class="grid-3" style="margin-top: 14px;">
            @foreach(($data['items'] ?? []) as $item)
                <blockquote class="card">
                    <p>"{{ $item['quote'] ?? '' }}"</p>
                    <footer class="muted" style="margin-top: 10px;">- {{ $item['name'] ?? 'Parent' }} @if(!empty($item['role'])) ({{ $item['role'] }}) @endif</footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>
