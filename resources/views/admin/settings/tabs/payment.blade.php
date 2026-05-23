{{-- Settings > Payments Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="payment">

    <section class="postbox">
        <div class="postbox-header">
            <h3>💳 Razorpay Payment Gateway</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Primary payment gateway supporting UPI, Cards, Wallets & Net Banking. API keys configured via environment variables.
            </p>

            <div class="admin-form-row">
                <label style="display: flex; align-items: center; gap: var(--space-md); font-weight: 600; cursor: pointer; margin-bottom: 0;">
                    <input type="hidden" name="settings[payment_razorpay_enabled]" value="0">
                    <input type="checkbox" name="settings[payment_razorpay_enabled]" value="1" {{ ($settings['payment_razorpay_enabled']->value ?? '1') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                    <span>Enable Razorpay Payment Gateway</span>
                </label>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 8px; margin-left: 28px;">Customers will see this as a payment option during checkout</p>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>🏪 Cash on Delivery (COD)</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Allow customers to pay when their order is delivered. Set limits and allowed areas.
            </p>

            <div class="admin-form-row">
                <label style="display: flex; align-items: center; gap: var(--space-md); font-weight: 600; cursor: pointer; margin-bottom: 0;">
                    <input type="hidden" name="settings[payment_cod_enabled]" value="0">
                    <input type="checkbox" class="cod-toggle" name="settings[payment_cod_enabled]" value="1" {{ ($settings['payment_cod_enabled']->value ?? '0') === '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                    <span>Enable Cash on Delivery</span>
                </label>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 8px; margin-left: 28px;">Customers can choose to pay when order is delivered</p>
            </div>

            <div style="margin-top: var(--space-2xl); padding: var(--space-lg); background: linear-gradient(90deg, rgba(15, 118, 110, 0.02) 0%, transparent 100%); border-radius: 8px; border: 1px solid var(--wp-border); opacity: {{ ($settings['payment_cod_enabled']->value ?? '0') === '1' ? '1' : '0.5' }}; transition: all var(--transition-fast);" class="cod-conditions">
                <h4 style="font-size: 14px; font-weight: 600; color: var(--wp-text); margin: 0 0 var(--space-lg);">COD Restrictions</h4>

                <div class="admin-form-grid-2">
                    <div class="admin-form-row">
                        <label for="payment_cod_min_order">Minimum Order Amount (₹)</label>
                        <input type="number" id="payment_cod_min_order" name="settings[payment_cod_min_order]" value="{{ $settings['payment_cod_min_order']->value ?? '0' }}" min="0" step="1" style="max-width: 100%;">
                        <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Set to 0 for no minimum</p>
                    </div>

                    <div class="admin-form-row">
                        <label for="payment_cod_max_order">Maximum Order Amount (₹)</label>
                        <input type="number" id="payment_cod_max_order" name="settings[payment_cod_max_order]" value="{{ $settings['payment_cod_max_order']->value ?? '' }}" min="0" step="1" style="max-width: 100%;">
                        <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Leave empty for no maximum</p>
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="payment_cod_allowed_pincodes">Allowed Pincodes</label>
                    <textarea id="payment_cod_allowed_pincodes" name="settings[payment_cod_allowed_pincodes]" placeholder="500001, 500002, 500003&#10;560001, 560002">{{ $settings['payment_cod_allowed_pincodes']->value ?? '' }}</textarea>
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Comma-separated pincodes. Leave empty to allow all areas.</p>
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save Payment Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codToggle = document.querySelector('.cod-toggle');
        const codConditions = document.querySelector('.cod-conditions');

        function updateCODState() {
            if (codConditions) {
                codConditions.style.opacity = codToggle.checked ? '1' : '0.5';
                codConditions.style.pointerEvents = codToggle.checked ? 'auto' : 'auto';
            }
        }

        if (codToggle) {
            codToggle.addEventListener('change', updateCODState);
            updateCODState(); // Initialize on load
        }
    });
</script>