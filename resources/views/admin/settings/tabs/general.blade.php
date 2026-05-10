{{-- Settings > General Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="general">

    <section class="postbox">
        <div class="postbox-header">
            <h3>Store Identity</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label for="store_name">Store Name</label>
                <input type="text" id="store_name" name="settings[store_name]" value="{{ $settings['store_name']->value ?? '' }}" placeholder="Your store name">
            </div>
            <div class="admin-form-row">
                <label for="store_logo">Store Logo URL</label>
                <input type="text" id="store_logo" name="settings[store_logo]" value="{{ $settings['store_logo']->value ?? '' }}" placeholder="https://...">
                @if(!empty($settings['store_logo']->value ?? ''))
                <div style="margin-top:8px;"><img src="{{ $settings['store_logo']->value }}" alt="Logo" style="max-height:60px; border:1px solid var(--wp-border); border-radius:4px;"></div>
                @endif
            </div>
            <div class="admin-form-row">
                <label for="store_email">Store Email</label>
                <input type="email" id="store_email" name="settings[store_email]" value="{{ $settings['store_email']->value ?? '' }}" placeholder="hello@numnam.in">
            </div>
            <div class="admin-form-row">
                <label for="store_phone">Store Phone</label>
                <input type="text" id="store_phone" name="settings[store_phone]" value="{{ $settings['store_phone']->value ?? '' }}" placeholder="+91 ...">
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>Store Address</h3>
        </div>
        <div class="inside">
            <div class="admin-form-row">
                <label for="store_address">Street Address</label>
                <textarea id="store_address" name="settings[store_address]" style="min-height:60px;">{{ $settings['store_address']->value ?? '' }}</textarea>
            </div>
            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="store_city">City</label>
                    <input type="text" id="store_city" name="settings[store_city]" value="{{ $settings['store_city']->value ?? '' }}">
                </div>
                <div class="admin-form-row">
                    <label for="store_state">State</label>
                    <input type="text" id="store_state" name="settings[store_state]" value="{{ $settings['store_state']->value ?? '' }}">
                </div>
            </div>
            <div class="admin-form-row">
                <label for="store_pincode">Pincode</label>
                <input type="text" id="store_pincode" name="settings[store_pincode]" value="{{ $settings['store_pincode']->value ?? '' }}" style="max-width:200px;">
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>Currency</h3>
        </div>
        <div class="inside">
            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="store_currency">Currency Code</label>
                    <input type="text" id="store_currency" name="settings[store_currency]" value="{{ $settings['store_currency']->value ?? 'INR' }}" style="max-width:120px;">
                </div>
                <div class="admin-form-row">
                    <label for="store_currency_symbol">Currency Symbol</label>
                    <input type="text" id="store_currency_symbol" name="settings[store_currency_symbol]" value="{{ $settings['store_currency_symbol']->value ?? '₹' }}" style="max-width:80px;">
                </div>
            </div>
        </div>
    </section>

    <div style="margin-top:16px;">
        <button class="admin-btn" type="submit">Save General Settings</button>
    </div>
</form>