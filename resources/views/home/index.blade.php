<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $homepage?->meta_title ?? 'NumNam' }}</title>
    <meta name="description" content="{{ $homepage?->meta_description ?? 'NumNam homepage' }}">
    <style>
        body { margin: 0; font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; color: #1f2937; background: #f8fafc; }
        .wrap { width: min(1120px, 92vw); margin: 0 auto; }
        section { padding: 56px 0; }
        h1, h2, h3 { margin: 0 0 12px; }
        p { margin: 0 0 10px; line-height: 1.6; }
        .btn { display: inline-block; padding: 11px 16px; border-radius: 10px; text-decoration: none; font-weight: 600; }
        .btn-primary { background: #0f766e; color: #fff; }
        .btn-light { background: #fff; color: #0f766e; }
        .grid-2 { display: grid; gap: 20px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .grid-3 { display: grid; gap: 16px; grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; }
        .muted { color: #6b7280; }
        @media (max-width: 900px) {
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            section { padding: 40px 0; }
        }
    </style>
</head>
<body>
    @forelse($sections as $section)
        @includeIf('home.sections.' . $section->section_type, ['section' => $section])
    @empty
        <section>
            <div class="wrap card">
                <h2>Homepage not configured yet</h2>
                <p class="muted">Create a homepage and add sections from the admin dashboard.</p>
            </div>
        </section>
    @endforelse
</body>
</html>
