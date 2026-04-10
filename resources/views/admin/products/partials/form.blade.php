@php($isEdit = isset($product))

<div class="admin-editor-layout">
    {{-- Main Column --}}
    <div class="admin-editor-main">
        {{-- Product Name --}}
        <div class="admin-form-row" style="margin-bottom:16px;">
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Product name" required style="font-size:18px; padding:8px 12px; width:100%; border:1px solid #8c8f94; border-radius:4px;">
        </div>
        <div class="admin-form-row" style="margin-bottom:16px;">
            <label style="font-size:12px; color:var(--wp-muted);">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $product->slug ?? '') }}" placeholder="Auto-generated from name if blank" style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 8px; font-size:13px;">
        </div>

        {{-- Short Description --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Short Description</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="short_description" id="short_description" style="width:100%; min-height:100px; border:none; padding:8px 12px; resize:vertical; font-size:13px; font-family:inherit;">{{ old('short_description', $product->short_description ?? '') }}</textarea>
            </div>
        </section>

        {{-- Full Description (WYSIWYG) --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Description</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="description" id="product_description_editor" style="visibility:hidden;">{{ old('description', $product->description ?? '') }}</textarea>
            </div>
        </section>

        {{-- Ingredients --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Ingredients</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="ingredients" id="ingredients" style="width:100%; min-height:100px; border:none; padding:8px 12px; resize:vertical; font-size:13px; font-family:inherit;">{{ old('ingredients', $product->ingredients ?? '') }}</textarea>
            </div>
        </section>

        {{-- Product Data (WooCommerce-style tabs) --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Product Data</h3>
            </div>
            <div class="inside">
                <div class="admin-form-grid-2">
                    <div class="admin-form-row">
                        <label>Price (₹)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" placeholder="Regular price" required>
                    </div>
                    <div class="admin-form-row">
                        <label>Sale Price (₹)</label>
                        <input type="number" step="0.01" min="0" name="sale_price" value="{{ old('sale_price', $product->sale_price ?? '') }}" placeholder="Leave blank for no sale">
                    </div>
                    <div class="admin-form-row">
                        <label>Stock Quantity</label>
                        <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required>
                    </div>
                    <div class="admin-form-row">
                        <label>Age Group</label>
                        <input type="text" name="age_group" value="{{ old('age_group', $product->age_group ?? '6M+') }}" placeholder="e.g. 6M+" required>
                    </div>
                </div>
            </div>
        </section>

        {{-- Nutrition Facts --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Nutrition Facts</h3>
            </div>
            <div class="inside">
                <p class="admin-field-desc">Enter as JSON, e.g. {"protein":"13g","carbs":"20g"}</p>
                <textarea name="nutrition_facts" style="width:100%; min-height:80px; font-family:monospace; font-size:12px;" placeholder='{"protein":"13g"}'>{{ old('nutrition_facts', isset($product) ? json_encode($product->nutrition_facts ?? [], JSON_PRETTY_PRINT) : '') }}</textarea>
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
                <label class="admin-toggle-label" style="margin-bottom:12px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))>
                    <span>Active</span>
                </label>
                <label class="admin-toggle-label" style="margin-bottom:16px;">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))>
                    <span>Featured</span>
                </label>
                <button class="admin-btn" type="submit" style="width:100%;">{{ $isEdit ? 'Update Product' : 'Publish Product' }}</button>
            </div>
        </section>

        {{-- Category --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Category</h3>
            </div>
            <div class="inside">
                <select name="category_id" style="width:100%;">
                    <option value="">— No Category —</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? null) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        {{-- Product Type --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Type</h3>
            </div>
            <div class="inside">
                <select name="type" style="width:100%;" required>
                    @foreach(['puree' => 'Puree', 'puffs' => 'Puffs', 'cookies' => 'Cookies'] as $key => $label)
                    <option value="{{ $key }}" @selected(old('type', $product->type ?? 'puree') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </section>

        {{-- Product Image --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Product Image</h3>
            </div>
            <div class="inside">
                <input type="hidden" name="image" id="productImageInput" value="{{ old('image', $product->image ?? '') }}">
                <button type="button" class="mp-choose-btn" id="chooseProductImage">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    Choose Image
                </button>
                <div class="mp-preview-wrap" id="productImagePreview">
                    @if(!empty($product->image ?? null))
                    <img src="{{ $product->image_url ?? $product->image }}" alt="">
                    <button type="button" class="mp-preview-remove" onclick="MediaPickerField.clearSingle(this)">&times;</button>
                    @endif
                </div>
            </div>
        </section>

        {{-- Gallery --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Gallery</h3>
            </div>
            <div class="inside">
                <button type="button" class="mp-choose-btn" id="chooseGalleryImages">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    Add Images
                </button>
                <textarea name="gallery" id="galleryInput" style="display:none;">{{ old('gallery', isset($product) ? implode(PHP_EOL, $product->gallery ?? []) : '') }}</textarea>
                <div class="mp-gallery-grid" id="galleryGrid"></div>
            </div>
        </section>

        {{-- Badges --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Badges</h3>
            </div>
            <div class="inside">
                <p class="admin-field-desc">Comma separated</p>
                <input type="text" name="badges" value="{{ old('badges', isset($product) ? implode(', ', $product->badges ?? []) : '') }}" placeholder="Organic, New, Best Seller" style="width:100%;">
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
    (function() {
        tinymce.init({
            selector: '#product_description_editor',
            license_key: 'gpl',
            height: 350,
            menubar: 'file edit view insert format tools table',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | blockquote table | code fullscreen | removeformat help',
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 14px; line-height: 1.6; }',
            branding: false,
            promotion: false,
            image_title: true,
            automatic_uploads: false,
            file_picker_types: 'image',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype === 'image') {
                    MediaPicker.open(function(media) {
                        callback(media.url, {
                            title: media.title || '',
                            alt: media.title || ''
                        });
                    });
                }
            },
        });

        // Media picker for product image
        MediaPickerField.bindSingle(
            document.getElementById('chooseProductImage'),
            document.getElementById('productImageInput'),
            document.getElementById('productImagePreview')
        );

        // Media picker for gallery
        MediaPickerField.bindGallery(
            document.getElementById('chooseGalleryImages'),
            document.getElementById('galleryInput'),
            document.getElementById('galleryGrid')
        );
    })();
</script>
@endpush