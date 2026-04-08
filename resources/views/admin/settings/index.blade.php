@extends('admin.layouts.app')

@section('title', 'Settings - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Site Settings</h2>
        <p class="admin-desc">General configuration for the storefront</p>
    </div>
    <a href="{{ route('admin.settings.create') }}" class="admin-btn" style="text-decoration:none;">Add Setting</a>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    @forelse($settings as $group => $items)
    <section class="admin-panel" style="margin-bottom:20px;">
        <h3 style="padding:10px 12px; margin:0; border-bottom:1px solid var(--wp-border); font-size:14px; font-weight:600; text-transform:capitalize;">
            {{ $group ?: 'General' }}
        </h3>
        <div style="padding:16px;">
            @foreach($items as $setting)
            <div class="admin-form-row">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <label for="setting_{{ $setting->key }}">
                        {{ ucwords(str_replace(['_', '-'], ' ', $setting->key)) }}
                        <span style="font-weight:400; color:var(--wp-muted); font-size:12px;">{{ $setting->key }}</span>
                    </label>
                    <button type="button" class="admin-btn-danger" style="font-size:11px;" onclick="if(confirm('Delete this setting?')){let f=document.createElement('form');f.method='POST';f.action='{{ route('admin.settings.destroy', $setting) }}';f.innerHTML='@csrf @method(\'DELETE\')';document.body.appendChild(f);f.submit();}">Remove</button>
                </div>
                @if(($setting->type ?? 'text') === 'textarea')
                <textarea id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" style="min-height:80px;">{{ $setting->value }}</textarea>
                @elseif(($setting->type ?? 'text') === 'boolean')
                <select id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]">
                    <option value="1" @selected($setting->value)>Yes</option>
                    <option value="0" @selected(!$setting->value)>No</option>
                </select>
                @else
                <input type="{{ ($setting->type ?? 'text') === 'number' ? 'number' : 'text' }}" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}">
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @empty
    <section class="admin-panel">
        <div class="admin-empty">
            <p>No settings configured yet.</p>
            <a href="{{ route('admin.settings.create') }}" class="admin-btn" style="text-decoration:none;">Add First Setting</a>
        </div>
    </section>
    @endforelse

    @if($settings->count())
    <div style="margin-top:12px;">
        <button class="admin-btn" type="submit">Save All Settings</button>
    </div>
    @endif
</form>
@endsection