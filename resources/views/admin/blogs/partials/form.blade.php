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
                <input type="hidden" name="content" id="blog_content_input">
                <div id="blog_content_editor" style="min-height:400px;"></div>
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
                <input type="hidden" name="featured_image" id="blogFeaturedImageInput" value="{{ old('featured_image', $blog->featured_image ?? '') }}">
                <button type="button" class="mp-choose-btn" id="chooseBlogImage">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    Choose Image
                </button>
                <div class="mp-preview-wrap" id="blogImagePreview">
                    @if(!empty($blog->featured_image ?? null))
                    <img src="{{ $blog->featured_image_url ?? $blog->featured_image }}" alt="">
                    <button type="button" class="mp-preview-remove" onclick="MediaPickerField.clearSingle(this)">&times;</button>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script type="application/json" id="__contentData">
    {
        !!json_encode(old('content', $blog - > content ?? '')) !!
    }
</script>
<script>
    (function() {
        var __contentHtml = JSON.parse(document.getElementById('__contentData').textContent);
        // Quill editor for blog content
        const contentEditor = new Quill('#blog_content_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, 4, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['blockquote', 'link', 'image', 'video'],
                    [{
                        align: []
                    }],
                    ['clean'],
                ],
            },
            placeholder: 'Write your blog post...',
        });
        contentEditor.root.innerHTML = __contentHtml;

        // Sync Quill → hidden input on form submit
        contentEditor.root.closest('form').addEventListener('submit', function() {
            document.getElementById('blog_content_input').value = contentEditor.root.innerHTML;
        });

        // Image handler: open media picker
        contentEditor.getModule('toolbar').addHandler('image', function() {
            MediaPicker.open(function(media) {
                const range = contentEditor.getSelection(true);
                contentEditor.insertEmbed(range.index, 'image', media.url);
                contentEditor.setSelection(range.index + 1);
            });
        });

        // Media picker for featured image
        MediaPickerField.bindSingle(
            document.getElementById('chooseBlogImage'),
            document.getElementById('blogFeaturedImageInput'),
            document.getElementById('blogImagePreview')
        );
    })();
</script>
@endpush