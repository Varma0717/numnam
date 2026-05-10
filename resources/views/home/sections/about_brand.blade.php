@php($data = $section->data)
<section>
    <div class="wrap grid-2">
        <div>
            <h2>{{ $data['heading'] ?? $section->title }}</h2>
            <p>{{ $data['body'] ?? $section->content }}</p>
        </div>
        <div class="card">
            <h3>Brand Highlights</h3>
            <ul>
                @foreach(($data['highlights'] ?? []) as $point)
                    <li>{{ $point }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
