@php($isEdit = isset($blog))

<div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px;">
    <div class="admin-form-row">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $blog->title ?? '') }}" placeholder="Blog post title" required>
    </div>

    <div class="admin-form-row">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $blog->slug ?? '') }}" placeholder="Auto-generated from title if blank">
    </div>

    <div class="admin-form-row">
        <label for="blog_category_id">Category</label>
        <select id="blog_category_id" name="blog_category_id">
            <option value="">No category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('blog_category_id', $blog->blog_category_id ?? null) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="admin-form-row">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            <option value="draft" @selected(old('status', $blog->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $blog->status ?? 'draft') === 'published')>Published</option>
        </select>
    </div>
</div>

<div class="admin-form-row">
    <label for="featured_image">Featured Image URL</label>
    <input type="text" id="featured_image" name="featured_image" value="{{ old('featured_image', $blog->featured_image ?? '') }}" placeholder="https://...">
    @if(!empty($blog->featured_image ?? null))
    <div style="margin-top:8px;">
        <img src="{{ $blog->featured_image }}" alt="" style="max-height:120px; border:1px solid var(--wp-border); border-radius:4px;">
    </div>
    @endif
</div>

<div class="admin-form-row">
    <label for="excerpt">Excerpt</label>
    <textarea id="excerpt" name="excerpt" style="min-height:80px;" placeholder="Short summary for listings">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
</div>

<div class="admin-form-row">
    <label for="content">Content</label>
    <textarea id="content" name="content" style="min-height:300px;" placeholder="Full blog post content (HTML supported)">{{ old('content', $blog->content ?? '') }}</textarea>
</div>

<div class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px;">
    <div class="admin-form-row">
        <label for="meta_title">Meta Title</label>
        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title ?? '') }}" placeholder="SEO title">
    </div>

    <div class="admin-form-row">
        <label for="meta_description">Meta Description</label>
        <input type="text" id="meta_description" name="meta_description" value="{{ old('meta_description', $blog->meta_description ?? '') }}" placeholder="SEO description">
    </div>
</div>

<div class="admin-form-row">
    <button class="admin-btn" type="submit">{{ $isEdit ? 'Update Post' : 'Create Post' }}</button>
    <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary" style="margin-left:8px; text-decoration:none;">Cancel</a>
</div>