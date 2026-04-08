@php($isEdit = isset($blog))

<div class="admin-editor-layout">
    {{-- Main Column --}}
    <div class="admin-editor-main">
        {{-- Title --}}
        <div class="admin-form-row" style="margin-bottom:16px;">
            <input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}" placeholder="Post title" required style="font-size:18px; padding:8px 12px; width:100%; border:1px solid #8c8f94; border-radius:4px;">
        </div>
        <div class="admin-form-row" style="margin-bottom:16px;">
            <label style="font-size:12px; color:var(--wp-muted);">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $blog->slug ?? '') }}" placeholder="Auto-generated from title if blank" style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 8px; font-size:13px;">
        </div>

        {{-- Excerpt --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Excerpt</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="excerpt" style="width:100%; min-height:80px; border:none; padding:8px 12px; resize:vertical; font-size:13px; font-family:inherit;" placeholder="Short summary for listings">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
            </div>
        </section>

        {{-- Content (WYSIWYG) --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Content</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="content" id="blog_content" class="wysiwyg-editor" style="width:100%; min-height:400px; border:none; padding:8px 12px; resize:vertical;">{{ old('content', $blog->content ?? '') }}</textarea>
            </div>
        </section>

        {{-- SEO --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>SEO</h3>
            </div>
            <div class="inside">
                <div class="admin-form-grid-2">
                    <div class="admin-form-row">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title ?? '') }}" placeholder="SEO title">
                    </div>
                    <div class="admin-form-row">
                        <label for="meta_description">Meta Description</label>
                        <input type="text" id="meta_description" name="meta_description" value="{{ old('meta_description', $blog->meta_description ?? '') }}" placeholder="SEO description">
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Sidebar --}}
    <div class="admin-editor-sidebar">
        {{-- Publish --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Publish</h3>
            </div>
            <div class="inside">
                <div class="admin-form-row" style="margin-bottom:12px;">
                    <label for="status">Status</label>
                    <select id="status" name="status" style="width:100%;" required>
                        <option value="draft" @selected(old('status', $blog->status ?? 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $blog->status ?? 'draft') === 'published')>Published</option>
                    </select>
                </div>
                <button class="admin-btn" type="submit" style="width:100%;">{{ $isEdit ? 'Update Post' : 'Publish Post' }}</button>
                @if($isEdit)
                <a href="{{ route('admin.blogs.index') }}" class="admin-btn-secondary" style="width:100%; text-align:center; display:block; margin-top:8px; text-decoration:none;">Cancel</a>
                @endif
            </div>
        </section>

        {{-- Category --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Category</h3>
            </div>
            <div class="inside">
                <select name="blog_category_id" style="width:100%;">
                    <option value="">— No Category —</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('blog_category_id', $blog->blog_category_id ?? null) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        {{-- Featured Image --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Featured Image</h3>
            </div>
            <div class="inside">
                <input type="text" name="featured_image" value="{{ old('featured_image', $blog->featured_image ?? '') }}" placeholder="Image URL" style="width:100%; margin-bottom:8px;">
                @if(!empty($blog->featured_image ?? null))
                <img src="{{ $blog->featured_image }}" alt="" style="max-width:100%; height:auto; border:1px solid var(--wp-border); border-radius:4px;">
                @endif
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.wysiwyg-editor',
        height: 400,
        menubar: false,
        plugins: 'lists link image code table media',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | blockquote link image media | code',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 15px; line-height:1.6; }',
        branding: false,
        promotion: false,
    });
</script>