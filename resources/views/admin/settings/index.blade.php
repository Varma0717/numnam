@extends('admin.layouts.app')

@section('title', 'Settings - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Site Settings</h2>
    <p class="admin-desc">General configuration for the storefront</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>Key</th>
            <th>Value</th>
            <th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @forelse($settings as $setting)
            <tr>
                <td><strong>{{ $setting->key }}</strong></td>
                <td>{{ \Illuminate\Support\Str::limit(is_array($setting->value) ? json_encode($setting->value) : $setting->value, 80) }}</td>
                <td>{{ $setting->updated_at->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No settings configured yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
