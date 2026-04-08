{{-- Settings > Payments Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="payment">

    <section class="postbox">
        <div class="postbox-header">
            <h3>Razorpay</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label>Status</label>
                <label class="admin-toggle">
                    <input type="hidden" name="settings[payment_razorpay_enabled]" value="0">
                    <input type="checkbox" name="settings[payment_razorpay_enabled]" value="1" {{ ($settings['payment_razorpay_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                    <span class="admin-toggle-slider"></span>
                    <span class="admin-toggle-label">Enable Razorpay</span>
                </label>
                <p class="admin-field-desc">Primary payment gateway for UPI, Cards, Wallets & Net Banking. API keys are configured via environment variables (<code>RAZORPAY_KEY_ID</code>, <code>RAZORPAY_KEY_SECRET</code>).</p>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>Cash on Delivery (COD)</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label>Status</label>
                <label class="admin-toggle">
                    <input type="hidden" name="settings[payment_cod_enabled]" value="0">
                    <input type="checkbox" name="settings[payment_cod_enabled]" value="1" {{ ($settings['payment_cod_enabled']->value ?? '0') === '1' ? 'checked' : '' }}>
                    <span class="admin-toggle-slider"></span>
                    <span class="admin-toggle-label">Enable Cash on Delivery</span>
                </label>
            </div>

            <div class="admin-form-row cod-condition" style="{{ ($settings['payment_cod_enabled']->value ?? '0') !== '1' ? 'opacity:0.5;' : '' }}">
                <label for="payment_cod_min_order">Minimum Order Amount (₹)</label>
                <input type="number" id="payment_cod_min_order" name="settings[payment_cod_min_order]" value="{{ $settings['payment_cod_min_order']->value ?? '0' }}" min="0" step="1" style="max-width:200px;">
                <p class="admin-field-desc">Set to 0 for no minimum. Customers must reach this amount to see COD.</p>
            </div>

            <div class="admin-form-row cod-condition" style="{{ ($settings['payment_cod_enabled']->value ?? '0') !== '1' ? 'opacity:0.5;' : '' }}">
                <label for="payment_cod_max_order">Maximum Order Amount (₹)</label>
                <input type="number" id="payment_cod_max_order" name="settings[payment_cod_max_order]" value="{{ $settings['payment_cod_max_order']->value ?? '' }}" min="0" step="1" style="max-width:200px;">
                <p class="admin-field-desc">Leave empty for no maximum. Orders above this won't see COD.</p>
            </div>

            <div class="admin-form-row cod-condition" style="{{ ($settings['payment_cod_enabled']->value ?? '0') !== '1' ? 'opacity:0.5;' : '' }}">
                <label for="payment_cod_allowed_pincodes">Allowed Pincodes</label>
                <textarea id="payment_cod_allowed_pincodes" name="settings[payment_cod_allowed_pincodes]" style="min-height:80px;" placeholder="500001, 500002, 500003">{{ $settings['payment_cod_allowed_pincodes']->value ?? '' }}</textarea>
                <p class="admin-field-desc">Comma-separated list of pincodes where COD is available. Leave empty to allow all pincodes.</p>
            </div>
        </div>
    </section>

    <div style="margin-top:16px;">
        <button class="admin-btn" type="submit">Save Payment Settings</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codToggle = document.querySelector('input[name="settings[payment_cod_enabled]"][type="checkbox"]');
        const codConditions = document.querySelectorAll('.cod-condition');
        if (codToggle) {
            codToggle.addEventListener('change', function() {
                codConditions.forEach(el => el.style.opacity = this.checked ? '1' : '0.5');
            });
        }
    });
</script>