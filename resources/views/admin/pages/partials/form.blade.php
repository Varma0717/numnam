@php($isEdit = isset($page))

<div class="admin-editor-layout">
    {{-- Main Column --}}
    <div class="admin-editor-main">
        {{-- Title --}}
        <div class="admin-form-row" style="margin-bottom:16px;">
            <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" placeholder="Page title" required style="font-size:18px; padding:8px 12px; width:100%; border:1px solid #8c8f94; border-radius:4px;">
        </div>
        <div class="admin-form-row" style="margin-bottom:16px;">
            <label style="font-size:12px; color:var(--wp-muted);">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="Auto-generated from title if blank" style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 8px; font-size:13px;">
        </div>

        {{-- Page Sections --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Page Sections</h3>
                <button type="button" class="admin-btn-secondary" onclick="addSection()" style="margin:0;">+ Add Section</button>
            </div>
            <div class="inside" id="sections-container">
                @php($sections = old('sections', isset($page) ? $page->sections->toArray() : []))
                @forelse($sections as $i => $section)
                <div class="section-row" style="border:1px solid var(--wp-border); padding:12px; border-radius:4px; margin-bottom:12px; background:#fafafa;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <strong style="font-size:13px;">Section {{ $i + 1 }}</strong>
                        <button type="button" class="admin-btn-danger" onclick="this.closest('.section-row').remove()">&times; Remove</button>
                    </div>
                    <div class="admin-form-grid-2">
                        <div class="admin-form-row">
                            <label>Section Key</label>
                            <input type="text" name="sections[{{ $i }}][section_key]" value="{{ $section['section_key'] ?? '' }}" placeholder="e.g. hero, features">
                        </div>
                        <div class="admin-form-row">
                            <label>Section Type</label>
                            <select name="sections[{{ $i }}][section_type]">
                                <option value="content" @selected(($section['section_type'] ?? '' )==='content' )>Content</option>
                                <option value="hero" @selected(($section['section_type'] ?? '' )==='hero' )>Hero</option>
                                <option value="cta" @selected(($section['section_type'] ?? '' )==='cta' )>CTA</option>
                                <option value="gallery" @selected(($section['section_type'] ?? '' )==='gallery' )>Gallery</option>
                                <option value="faq" @selected(($section['section_type'] ?? '' )==='faq' )>FAQ</option>
                            </select>
                        </div>
                    </div>
                    <div class="admin-form-row" style="margin-top:8px;">
                        <label>Title</label>
                        <input type="text" name="sections[{{ $i }}][title]" value="{{ $section['title'] ?? '' }}">
                    </div>
                    <div class="admin-form-row" style="margin-top:8px;">
                        <label>Content</label>
                        <textarea name="sections[{{ $i }}][content]" style="min-height:120px;">{{ $section['content'] ?? '' }}</textarea>
                    </div>
                    <label class="admin-toggle-label" style="margin-top:8px;">
                        <input type="hidden" name="sections[{{ $i }}][is_active]" value="0">
                        <input type="checkbox" name="sections[{{ $i }}][is_active]" value="1" @checked($section['is_active'] ?? true)>
                        <span>Active</span>
                    </label>
                </div>
                @empty
                <p class="admin-muted" id="no-sections-msg">No sections yet. Click "Add Section" to start building your page.</p>
                @endforelse
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
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title ?? '') }}" placeholder="SEO title">
                    </div>
                    <div class="admin-form-row">
                        <label>Meta Description</label>
                        <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description ?? '') }}" placeholder="SEO description">
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
                    <label>Status</label>
                    <select name="status" style="width:100%;" required>
                        <option value="draft" @selected(old('status', $page->status ?? 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $page->status ?? 'draft') === 'published')>Published</option>
                    </select>
                </div>
                <div class="admin-form-row" style="margin-bottom:12px;">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order ?? 0) }}" style="width:100%;">
                </div>
                <button class="admin-btn" type="submit" style="width:100%;">{{ $isEdit ? 'Update Page' : 'Create Page' }}</button>
            </div>
        </section>

        {{-- Template --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Template</h3>
            </div>
            <div class="inside">
                <select name="template" style="width:100%;">
                    <option value="" @selected(old('template', $page->template ?? '') === '')>Default</option>
                    <option value="landing" @selected(old('template', $page->template ?? '') === 'landing')>Landing Page</option>
                    <option value="full-width" @selected(old('template', $page->template ?? '') === 'full-width')>Full Width</option>
                    <option value="sidebar" @selected(old('template', $page->template ?? '') === 'sidebar')>With Sidebar</option>
                </select>
            </div>
        </section>
    </div>
</div>

<script>
    let sectionIndex = {
        {
            count($sections)
        }
    };

    function addSection() {
        const msg = document.getElementById('no-sections-msg');
        if (msg) msg.remove();

        const html = `<div class="section-row" style="border:1px solid var(--wp-border); padding:12px; border-radius:4px; margin-bottom:12px; background:#fafafa;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <strong style="font-size:13px;">New Section</strong>
            <button type="button" class="admin-btn-danger" onclick="this.closest('.section-row').remove()">&times; Remove</button>
        </div>
        <div class="admin-form-grid-2">
            <div class="admin-form-row"><label>Section Key</label><input type="text" name="sections[${sectionIndex}][section_key]" placeholder="e.g. hero, features"></div>
            <div class="admin-form-row"><label>Section Type</label><select name="sections[${sectionIndex}][section_type]"><option value="content">Content</option><option value="hero">Hero</option><option value="cta">CTA</option><option value="gallery">Gallery</option><option value="faq">FAQ</option></select></div>
        </div>
        <div class="admin-form-row" style="margin-top:8px;"><label>Title</label><input type="text" name="sections[${sectionIndex}][title]"></div>
        <div class="admin-form-row" style="margin-top:8px;"><label>Content</label><textarea name="sections[${sectionIndex}][content]" style="min-height:120px;"></textarea></div>
        <label class="admin-toggle-label" style="margin-top:8px;"><input type="hidden" name="sections[${sectionIndex}][is_active]" value="0"><input type="checkbox" name="sections[${sectionIndex}][is_active]" value="1" checked><span>Active</span></label>
    </div>`;
        document.getElementById('sections-container').insertAdjacentHTML('beforeend', html);
        sectionIndex++;
    }
</script>