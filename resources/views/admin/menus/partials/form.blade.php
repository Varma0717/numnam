@php($isEdit = isset($menu))

<div class="admin-editor-layout">
    {{-- Main Column --}}
    <div class="admin-editor-main">
        {{-- Menu Details --}}
        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Menu Details</h3>
            </div>
            <div class="inside">
                <div class="admin-form-grid-2">
                    <div class="admin-form-row">
                        <label>Menu Name</label>
                        <input type="text" name="name" value="{{ old('name', $menu->name ?? '') }}" placeholder="e.g. Header Navigation" required>
                    </div>
                    <div class="admin-form-row">
                        <label>Location</label>
                        <select name="location">
                            <option value="">— Select Location —</option>
                            <option value="header" @selected(old('location', $menu->location ?? '') === 'header')>Header</option>
                            <option value="footer" @selected(old('location', $menu->location ?? '') === 'footer')>Footer</option>
                            <option value="sidebar" @selected(old('location', $menu->location ?? '') === 'sidebar')>Sidebar</option>
                            <option value="mobile" @selected(old('location', $menu->location ?? '') === 'mobile')>Mobile</option>
                        </select>
                    </div>
                </div>
                <div class="admin-form-row" style="margin-top:8px;">
                    <label>Description</label>
                    <input type="text" name="description" value="{{ old('description', $menu->description ?? '') }}" placeholder="Optional description">
                </div>
            </div>
        </section>

        {{-- Menu Items --}}
        <section class="postbox">
            <div class="postbox-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h3>Menu Items</h3>
                <button type="button" class="admin-btn-secondary" onclick="addMenuItem()" style="margin:0;">+ Add Item</button>
            </div>
            <div class="inside" id="menu-items-container">
                @php($items = old('items', isset($menu) ? $menu->items->map(fn($item) => $item->toArray())->toArray() : []))
                @forelse($items as $i => $item)
                <div class="menu-item-row" style="display:block; padding:12px; margin-bottom:8px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span class="drag-handle" style="cursor:grab; color:var(--wp-muted);">☰</span>
                        <strong class="item-label" style="flex:1; margin-left:8px; font-size:13px;">{{ $item['label'] ?? 'Menu Item' }}</strong>
                        <button type="button" class="admin-btn-danger" onclick="this.closest('.menu-item-row').remove()">&times;</button>
                    </div>
                    <div class="admin-form-grid-2" style="gap:8px;">
                        <div class="admin-form-row">
                            <label style="font-size:12px;">Label</label>
                            <input type="text" name="items[{{ $i }}][label]" value="{{ $item['label'] ?? '' }}" placeholder="Menu item text">
                        </div>
                        <div class="admin-form-row">
                            <label style="font-size:12px;">URL</label>
                            <input type="text" name="items[{{ $i }}][url]" value="{{ $item['url'] ?? '' }}" placeholder="/path or https://...">
                        </div>
                        <div class="admin-form-row">
                            <label style="font-size:12px;">Page</label>
                            <select name="items[{{ $i }}][page_id]">
                                <option value="">— Custom URL —</option>
                                @foreach($pages as $page)
                                <option value="{{ $page->id }}" @selected(($item['page_id'] ?? '' )==$page->id)>{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-form-row">
                            <label style="font-size:12px;">Target</label>
                            <select name="items[{{ $i }}][target]">
                                <option value="_self" @selected(($item['target'] ?? '_self' )==='_self' )>Same Window</option>
                                <option value="_blank" @selected(($item['target'] ?? '_self' )==='_blank' )>New Tab</option>
                            </select>
                        </div>
                    </div>
                    <label class="admin-toggle-label" style="margin-top:8px;">
                        <input type="hidden" name="items[{{ $i }}][is_active]" value="0">
                        <input type="checkbox" name="items[{{ $i }}][is_active]" value="1" @checked($item['is_active'] ?? true)>
                        <span>Active</span>
                    </label>
                </div>
                @empty
                <p class="admin-muted" id="no-items-msg">No items. Click "+ Add Item" to build your menu.</p>
                @endforelse
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
                <label class="admin-toggle-label" style="margin-bottom:16px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $menu->is_active ?? true))>
                    <span>Active</span>
                </label>
                <button class="admin-btn" type="submit" style="width:100%;">{{ $isEdit ? 'Update Menu' : 'Create Menu' }}</button>
            </div>
        </section>

        {{-- Quick Add from Pages --}}
        <section class="postbox">
            <div class="postbox-header">
                <h3>Add from Pages</h3>
            </div>
            <div class="inside">
                <p class="admin-field-desc">Quickly add published pages as menu items.</p>
                @foreach($pages as $page)
                <label style="display:flex; align-items:center; gap:6px; padding:4px 0; font-size:13px; cursor:pointer;">
                    <input type="checkbox" class="quick-add-page" data-title="{{ $page->title }}" data-slug="/{{ $page->slug }}" data-page-id="{{ $page->id }}">
                    {{ $page->title }}
                </label>
                @endforeach
                <button type="button" class="admin-btn-secondary" style="margin-top:8px; width:100%;" onclick="addSelectedPages()">Add to Menu</button>
            </div>
        </section>
    </div>
</div>

<script>
    let itemIndex = {
        {
            count($items)
        }
    };

    function addMenuItem(label, url, pageId) {
        const msg = document.getElementById('no-items-msg');
        if (msg) msg.remove();

        label = label || '';
        url = url || '';
        pageId = pageId || '';

        const html = `<div class="menu-item-row" style="display:block; padding:12px; margin-bottom:8px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
            <span class="drag-handle" style="cursor:grab; color:var(--wp-muted);">☰</span>
            <strong class="item-label" style="flex:1; margin-left:8px; font-size:13px;">${label || 'New Item'}</strong>
            <button type="button" class="admin-btn-danger" onclick="this.closest('.menu-item-row').remove()">&times;</button>
        </div>
        <div class="admin-form-grid-2" style="gap:8px;">
            <div class="admin-form-row"><label style="font-size:12px;">Label</label><input type="text" name="items[${itemIndex}][label]" value="${label}" placeholder="Menu item text"></div>
            <div class="admin-form-row"><label style="font-size:12px;">URL</label><input type="text" name="items[${itemIndex}][url]" value="${url}" placeholder="/path or https://..."></div>
            <div class="admin-form-row"><label style="font-size:12px;">Page</label><select name="items[${itemIndex}][page_id]"><option value="">— Custom URL —</option>@foreach($pages as $page)<option value="{{ $page->id }}">${pageId == '{{ $page->id }}' ? 'selected' : ''}>{{ $page->title }}</option>@endforeach</select></div>
            <div class="admin-form-row"><label style="font-size:12px;">Target</label><select name="items[${itemIndex}][target]"><option value="_self">Same Window</option><option value="_blank">New Tab</option></select></div>
        </div>
        <label class="admin-toggle-label" style="margin-top:8px;"><input type="hidden" name="items[${itemIndex}][is_active]" value="0"><input type="checkbox" name="items[${itemIndex}][is_active]" value="1" checked><span>Active</span></label>
    </div>`;
        document.getElementById('menu-items-container').insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    function addSelectedPages() {
        document.querySelectorAll('.quick-add-page:checked').forEach(function(cb) {
            addMenuItem(cb.dataset.title, cb.dataset.slug, cb.dataset.pageId);
            cb.checked = false;
        });
    }
</script>