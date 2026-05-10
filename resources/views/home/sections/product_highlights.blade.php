@php($data = $section->data)
<section style="background: #f0fdfa;">
    <div class="wrap">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <div class="grid-3" style="margin-top: 14px;">
            @forelse(($section->render_items ?? collect()) as $product)
                <article class="card">
                    <h3>{{ $product->name }}</h3>
                    <p class="muted">{{ $product->slug }}</p>
                    <p><strong>${{ number_format((float) ($product->sale_price ?? $product->price), 2) }}</strong></p>
                </article>
            @empty
                <div class="card">No products configured.</div>
            @endforelse
        </div>
    </div>
</section>
