@php($isEdit = isset($plan))

<div class="admin-editor-layout">
    <div class="admin-editor-main">
        <div class="admin-form-row" style="margin-bottom:16px;">
            <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" placeholder="Plan name" required style="font-size:18px; padding:8px 12px; width:100%; border:1px solid #8c8f94; border-radius:4px;">
        </div>

        <div class="admin-form-row" style="margin-bottom:16px;">
            <label style="font-size:12px; color:var(--wp-muted);">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" placeholder="Auto-generated from name if blank" style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 8px; font-size:13px;">
        </div>

        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Description</h3>
            </div>
            <div class="inside" style="padding:0;">
                <textarea name="description" style="width:100%; min-height:140px; border:none; padding:10px 12px; resize:vertical; font-size:13px; font-family:inherit;">{{ old('description', $plan->description ?? '') }}</textarea>
            </div>
        </section>

        <section class="postbox" style="margin-bottom:16px;">
            <div class="postbox-header">
                <h3>Features</h3>
            </div>
            <div class="inside">
                <p class="admin-field-desc">One feature per line.</p>
                <textarea name="features" style="width:100%; min-height:180px; resize:vertical;">{{ old('features', isset($plan) && is_array($plan->features) ? implode(PHP_EOL, $plan->features) : '') }}</textarea>
            </div>
        </section>
    </div>

    <div class="admin-editor-sidebar">
        <section class="postbox">
            <div class="postbox-header">
                <h3>Publish</h3>
            </div>
            <div class="inside">
                <label class="admin-toggle-label" style="margin-bottom:16px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active ?? true))>
                    <span>Active</span>
                </label>
                <button class="admin-btn" type="submit" style="width:100%;">{{ $isEdit ? 'Update Plan' : 'Publish Plan' }}</button>
            </div>
        </section>

        <section class="postbox">
            <div class="postbox-header">
                <h3>Plan Details</h3>
            </div>
            <div class="inside admin-form-grid-2" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div class="admin-form-row" style="grid-column: 1 / -1;">
                    <label>Price (Rs)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $plan->price ?? '') }}" required>
                </div>
                <div class="admin-form-row" style="grid-column: 1 / -1;">
                    <label>Duration</label>
                    <input type="text" name="duration" value="{{ old('duration', $plan->duration ?? '1 month') }}" placeholder="e.g. 1 month">
                </div>
                <div class="admin-form-row" style="grid-column: 1 / -1;">
                    <label>Billing Cycle</label>
                    <select name="billing_cycle" required>
                        @php($cycle = old('billing_cycle', $plan->billing_cycle ?? 'monthly'))
                        <option value="weekly" @selected($cycle === 'weekly')>Weekly</option>
                        <option value="monthly" @selected($cycle === 'monthly')>Monthly</option>
                        <option value="quarterly" @selected($cycle === 'quarterly')>Quarterly</option>
                        <option value="yearly" @selected($cycle === 'yearly')>Yearly</option>
                        <option value="one_time" @selected($cycle === 'one_time')>One Time</option>
                    </select>
                </div>
                <div class="admin-form-row" style="grid-column: 1 / -1;">
                    <label>Sort Order</label>
                    <input type="number" min="0" step="1" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
                </div>
            </div>
        </section>

        @if($isEdit)
        <section class="postbox">
            <div class="postbox-header">
                <h3>Linked Products</h3>
            </div>
            <div class="inside">
                <p class="admin-muted" style="margin:0;">{{ $plan->products()->count() }} products currently linked to this plan.</p>
                <p class="admin-field-desc" style="margin-top:8px;">Product linking can still be managed through API tools if needed.</p>
            </div>
        </section>
        @endif
    </div>
</div>
