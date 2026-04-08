@php($isEdit = isset($category))

<div class="admin-form-row">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" placeholder="Category name" required>
</div>

<div class="admin-form-row">
    <label for="slug">Slug</label>
    <input type="text" id="slug" name="slug" value="{{ old('slug', $category->slug ?? '') }}" placeholder="Auto-generated from name if blank">
</div>

<div class="admin-form-row">
    <label for="image">Image URL</label>
    <input type="text" id="image" name="image" value="{{ old('image', $category->image ?? '') }}" placeholder="https://...">
    @if(!empty($category->image ?? null))
    <div style="margin-top:8px;">
        <img src="{{ $category->image }}" alt="" style="max-height:80px; border:1px solid var(--wp-border); border-radius:4px;">
    </div>
    @endif
</div>

<div class="admin-form-row">
    <label style="display:inline-flex; align-items:center; gap:6px; font-weight:400;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
        Active
    </label>
</div>

<div class="admin-form-row">
    <button class="admin-btn" type="submit">{{ $isEdit ? 'Update Category' : 'Create Category' }}</button>
    <a href="{{ route('admin.categories.index') }}" class="admin-btn-secondary" style="margin-left:8px; text-decoration:none;">Cancel</a>
</div>