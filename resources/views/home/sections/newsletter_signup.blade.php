@php($data = $section->data)
<section>
    <div class="wrap card">
        <h2>{{ $data['heading'] ?? $section->title }}</h2>
        <p>{{ $data['text'] ?? $section->content }}</p>
        <form method="post" action="#" style="display: flex; gap: 10px; margin-top: 12px; flex-wrap: wrap;">
            <input type="email" placeholder="{{ $data['placeholder'] ?? 'Enter your email' }}" style="padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 10px; min-width: 250px;">
            <button type="submit" class="btn btn-primary">{{ $data['button_label'] ?? 'Subscribe' }}</button>
        </form>
    </div>
</section>
