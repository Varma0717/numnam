@php($data = $section->data)
<section>
    <div class="wrap">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <div class="grid-3" style="margin-top: 14px;">
            @foreach(($data['items'] ?? []) as $item)
                <div class="card">{{ $item }}</div>
            @endforeach
        </div>
    </div>
</section>
