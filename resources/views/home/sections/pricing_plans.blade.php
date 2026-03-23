@php($data = $section->data)
<section style="background: #ecfeff;">
    <div class="wrap">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <div class="grid-3" style="margin-top: 14px;">
            @forelse(($section->render_items ?? collect()) as $plan)
                <article class="card">
                    <h3>{{ $plan->name }}</h3>
                    <p class="muted">{{ ucfirst(str_replace('_', ' ', $plan->billing_cycle)) }}</p>
                    <p><strong>${{ number_format((float) $plan->price, 2) }}</strong></p>
                </article>
            @empty
                <div class="card">No plans configured.</div>
            @endforelse
        </div>
    </div>
</section>
