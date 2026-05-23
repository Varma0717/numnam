{{-- Settings > General Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="general">

    <section class="postbox">
        <div class="postbox-header">
            <h3>🏪 Store Identity</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Configure your store's basic identity information including name, logo, and contact details.
            </p>

            <div class="admin-form-row">
                <label for="store_name">Store Name <span style="color: var(--wp-error);">*</span></label>
                <input type="text" id="store_name" name="settings[store_name]" value="{{ $settings['store_name']->value ?? '' }}" placeholder="e.g. NumNam Foods" required>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Your store name displayed across the website</p>
            </div>

            <div class="admin-form-row">
                <label for="store_logo">Store Logo URL</label>
                <input type="text" id="store_logo" name="settings[store_logo]" value="{{ $settings['store_logo']->value ?? '' }}" placeholder="https://example.com/logo.png">
                @if(!empty($settings['store_logo']->value ?? ''))
                <div style="margin-top: var(--space-lg); padding: var(--space-lg); background: #f9f9f9; border-radius: 8px; border: 1px solid var(--wp-border);">
                    <p style="font-size: 12px; color: var(--wp-muted); margin: 0 0 8px;">Current Logo:</p>
                    <img src="{{ $settings['store_logo']->value }}" alt="Logo" style="max-height: 80px; max-width: 200px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
                @endif
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Logo displayed in header. Use HTTPS URLs for best results.</p>
            </div>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="store_email">Store Email <span style="color: var(--wp-error);">*</span></label>
                    <input type="email" id="store_email" name="settings[store_email]" value="{{ $settings['store_email']->value ?? '' }}" placeholder="contact@numnam.com" required>
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Primary contact email for customers</p>
                </div>

                <div class="admin-form-row">
                    <label for="store_phone">Store Phone <span style="color: var(--wp-error);">*</span></label>
                    <input type="text" id="store_phone" name="settings[store_phone]" value="{{ $settings['store_phone']->value ?? '' }}" placeholder="+91 63099 20111" required>
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Include country code for international numbers</p>
                </div>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>📍 Store Address</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Physical address displayed to customers for delivery and contact purposes.
            </p>

            <div class="admin-form-row">
                <label for="store_address">Street Address</label>
                <textarea id="store_address" name="settings[store_address]" placeholder="123 Main Street, Suite 100&#10;City, State, Country">{{ $settings['store_address']->value ?? '' }}</textarea>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Full mailing address for your store</p>
            </div>

            <div class="admin-form-grid-3">
                <div class="admin-form-row">
                    <label for="store_city">City</label>
                    <input type="text" id="store_city" name="settings[store_city]" value="{{ $settings['store_city']->value ?? '' }}" placeholder="Bangalore">
                </div>
                <div class="admin-form-row">
                    <label for="store_state">State / Province</label>
                    <input type="text" id="store_state" name="settings[store_state]" value="{{ $settings['store_state']->value ?? '' }}" placeholder="Karnataka">
                </div>
                <div class="admin-form-row">
                    <label for="store_pincode">Postal Code</label>
                    <input type="text" id="store_pincode" name="settings[store_pincode]" value="{{ $settings['store_pincode']->value ?? '' }}" placeholder="560001">
                </div>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>💱 Currency Settings</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Configure currency code and symbol for price display throughout the store.
            </p>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="store_currency">Currency Code <span style="color: var(--wp-error);">*</span></label>
                    <input type="text" id="store_currency" name="settings[store_currency]" value="{{ $settings['store_currency']->value ?? 'INR' }}" placeholder="INR" required style="max-width: 100%;">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">ISO 4217 code (INR, USD, EUR, etc.)</p>
                </div>
                <div class="admin-form-row">
                    <label for="store_currency_symbol">Currency Symbol <span style="color: var(--wp-error);">*</span></label>
                    <input type="text" id="store_currency_symbol" name="settings[store_currency_symbol]" value="{{ $settings['store_currency_symbol']->value ?? '₹' }}" placeholder="₹" required style="max-width: 100%;">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Symbol displayed with prices (₹, $, €, etc.)</p>
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save General Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>