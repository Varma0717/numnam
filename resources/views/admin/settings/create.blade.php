@extends('admin.layouts.app')

@section('title', 'Add Setting - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Add Setting</h2>
    <p class="admin-desc">Create a new site configuration key</p>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.settings.store') }}" style="padding:16px;">
        @csrf

        <div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px;">
            <div class="admin-form-row">
                <label for="key">Key</label>
                <input type="text" id="key" name="key" value="{{ old('key') }}" placeholder="e.g. site_name" required>
            </div>

            <div class="admin-form-row">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="text" @selected(old('type')==='text' )>Text</option>
                    <option value="textarea" @selected(old('type')==='textarea' )>Textarea</option>
                    <option value="boolean" @selected(old('type')==='boolean' )>Boolean</option>
                    <option value="number" @selected(old('type')==='number' )>Number</option>
                </select>
            </div>
        </div>

        <div class="admin-form-row">
            <label for="value">Value</label>
            <textarea id="value" name="value" style="min-height:80px;" placeholder="Setting value">{{ old('value') }}</textarea>
        </div>

        <div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px;">
            <div class="admin-form-row">
                <label for="group">Group</label>
                <input type="text" id="group" name="group" value="{{ old('group') }}" placeholder="e.g. general, seo, social">
            </div>

            <div class="admin-form-row" style="align-self:end;">
                <label style="display:inline-flex; align-items:center; gap:6px; font-weight:400;">
                    <input type="checkbox" name="is_public" value="1" @checked(old('is_public'))>
                    Public (visible to frontend)
                </label>
            </div>
        </div>

        <div class="admin-form-row">
            <button class="admin-btn" type="submit">Create Setting</button>
            <a href="{{ route('admin.settings.index') }}" class="admin-btn-secondary" style="margin-left:8px; text-decoration:none;">Cancel</a>
        </div>
    </form>
</section>
@endsection