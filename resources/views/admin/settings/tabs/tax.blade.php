{{-- Settings > Tax Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="tax">

    <section class="postbox">
        <div class="postbox-header">
            <h3>GST Configuration</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label>Status</label>
                <label class="admin-toggle">
                    <input type="hidden" name="settings[tax_gst_enabled]" value="0">
                    <input type="checkbox" name="settings[tax_gst_enabled]" value="1" {{ ($settings['tax_gst_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                    <span class="admin-toggle-slider"></span>
                    <span class="admin-toggle-label">Enable GST</span>
                </label>
            </div>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="tax_gst_rate">GST Rate (%)</label>
                    <input type="number" id="tax_gst_rate" name="settings[tax_gst_rate]" value="{{ $settings['tax_gst_rate']->value ?? '18' }}" min="0" max="100" step="0.01" style="max-width:120px;">
                    <p class="admin-field-desc">Applied uniformly to all products.</p>
                </div>
                <div class="admin-form-row">
                    <label>Price Display</label>
                    <label class="admin-toggle">
                        <input type="hidden" name="settings[tax_inclusive]" value="0">
                        <input type="checkbox" name="settings[tax_inclusive]" value="1" {{ ($settings['tax_inclusive']->value ?? '1') === '1' ? 'checked' : '' }}>
                        <span class="admin-toggle-slider"></span>
                        <span class="admin-toggle-label">Prices include GST</span>
                    </label>
                    <p class="admin-field-desc">If enabled, product prices already include GST. The tax is extracted and shown as a line item at checkout. If disabled, GST is added on top of product prices.</p>
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top:16px;">
        <button class="admin-btn" type="submit">Save Tax Settings</button>
    </div>
</form>