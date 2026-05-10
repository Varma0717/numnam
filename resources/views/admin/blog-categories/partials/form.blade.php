@php($isEdit = isset($blogCategory))

<div class="admin-form-row">
    <label for="name">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $blogCategory->name ?? '') }}" placeholder="Category name" required>
</div>

<div class="admin-form-row">
    <label for="slug">Slug</label>
    <input type="text" id="slug" name="slug" value="{{ old('slug', $blogCategory->slug ?? '') }}" placeholder="Auto-generated from name if blank">
</div>

<div class="admin-form-row">
    <label for="parent_id">Parent Category</label>
    <select id="parent_id" name="parent_id">
        <option value="">None (top-level)</option>
        @foreach($parents as $parent)
        <option value="{{ $parent->id }}" @selected(old('parent_id', $blogCategory->parent_id ?? null) == $parent->id)>{{ $parent->name }}</option>
        @endforeach
    </select>
</div>

<div class="admin-form-row">
    <label for="description">Description</label>
    <textarea id="description" name="description" style="min-height:80px;" placeholder="Optional description">{{ old('description', $blogCategory->description ?? '') }}</textarea>
</div>

<div class="admin-form-row">
    <button class="admin-btn" type="submit">{{ $isEdit ? 'Update Category' : 'Create Category' }}</button>
    <a href="{{ route('admin.blog-categories.index') }}" class="admin-btn-secondary" style="margin-left:8px; text-decoration:none;">Cancel</a>
</div>