{{-- Settings > Tax Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="tax">

    <section class="postbox">
        <div class="postbox-header">
            <h3>🧮 GST Configuration (India)</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Configure Goods and Services Tax (GST) settings for your store. GST is applicable for all products and services sold in India.
            </p>

            <div class="admin-form-row">
                <label style="display: flex; align-items: center; gap: var(--space-md); font-weight: 600; cursor: pointer; margin-bottom: 0;">
                    <input type="hidden" name="settings[tax_gst_enabled]" value="0">
                    <input type="checkbox" class="gst-toggle" name="settings[tax_gst_enabled]" value="1" {{ ($settings['tax_gst_enabled']->value ?? '0') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                    <span>Enable GST Calculation</span>
                </label>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 8px; margin-left: 28px;">Enables GST calculation and display on product prices</p>
            </div>

            <div style="margin-top: var(--space-2xl); padding: var(--space-lg); background: linear-gradient(90deg, rgba(15, 118, 110, 0.02) 0%, transparent 100%); border-radius: 8px; border: 1px solid var(--wp-border); opacity: {{ ($settings['tax_gst_enabled']->value ?? '0') === '1' ? '1' : '0.5' }}; transition: all var(--transition-fast);" class="gst-conditions">
                <h4 style="font-size: 14px; font-weight: 600; color: var(--wp-text); margin: 0 0 var(--space-lg);">GST Settings</h4>

                <div class="admin-form-grid-2">
                    <div class="admin-form-row">
                        <label for="tax_gst_rate">GST Rate (%)</label>
                        <input type="number" id="tax_gst_rate" name="settings[tax_gst_rate]" value="{{ $settings['tax_gst_rate']->value ?? '18' }}" min="0" max="100" step="0.01" style="max-width: 100%;">
                        <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Applied uniformly to all products (e.g., 5%, 12%, 18%, 28%)</p>
                    </div>

                    <div class="admin-form-row">
                        <label>Price Display Method</label>
                        <div style="display: flex; flex-direction: column; gap: var(--space-md); margin-top: var(--space-md);">
                            <label style="display: flex; align-items: center; gap: var(--space-sm); font-weight: 400; cursor: pointer;">
                                <input type="hidden" name="settings[tax_inclusive]" value="0">
                                <input type="radio" name="tax_display" id="inclusive" value="inclusive" {{ ($settings['tax_inclusive']->value ?? '1') === '1' ? 'checked' : '' }} style="cursor: pointer;">
                                <span>Prices include GST</span>
                            </label>
                            <p style="font-size: 12px; color: var(--wp-muted); margin: 0; margin-left: 24px;">Product prices shown already include GST</p>

                            <label style="display: flex; align-items: center; gap: var(--space-sm); font-weight: 400; cursor: pointer; margin-top: var(--space-md);">
                                <input type="radio" name="tax_display" id="exclusive" value="exclusive" {{ ($settings['tax_inclusive']->value ?? '1') !== '1' ? 'checked' : '' }} style="cursor: pointer;" onchange="document.querySelector('input[name=\'settings[tax_inclusive]\'').value = '0';">
                                <span>Prices exclude GST (Add at Checkout)</span>
                            </label>
                            <p style="font-size: 12px; color: var(--wp-muted); margin: 0; margin-left: 24px;">Product prices shown are before GST; GST added at checkout</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save Tax Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gstToggle = document.querySelector('.gst-toggle');
        const gstConditions = document.querySelector('.gst-conditions');

        function updateGSTState() {
            if (gstConditions) {
                gstConditions.style.opacity = gstToggle.checked ? '1' : '0.5';
            }
        }

        if (gstToggle) {
            gstToggle.addEventListener('change', updateGSTState);
            updateGSTState(); // Initialize on load
        }
    });
</script>