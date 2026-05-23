{{-- Tracking & Analytics Settings Tab
   Admin settings for Google Pixel, Google Analytics, Facebook Pixel, and custom head code
   
   Usage in settings index:
   @include('admin.settings.tabs.tracking')
--}}

<x-admin.form-section title="Google Pixel Code" icon="code">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Add your Google Pixel tracking code. This will be injected into the `&lt;head&gt;` of your storefront.
    </p>

    <x-admin.textarea
        name="settings[tracking_google_pixel]"
        label="Google Pixel Code"
        value="{{ $settings['tracking_google_pixel']->value ?? '' }}"
        placeholder="Paste your Google Pixel code here (usually starts with <!-- Facebook Pixel --&gt;)"
        rows="5"
        hint="Find this code in your Facebook Pixel settings" />
</x-admin.form-section>

<x-admin.form-section title="Google Analytics Code" icon="chart-line">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Add your Google Analytics tracking code. This will be injected into the `&lt;head&gt;` of your storefront.
    </p>

    <x-admin.textarea
        name="settings[tracking_google_analytics]"
        label="Google Analytics Code"
        value="{{ $settings['tracking_google_analytics']->value ?? '' }}"
        placeholder="Paste your Google Analytics code here (usually starts with <!-- Global site tag --&gt;)"
        rows="5"
        hint="Find this code in your Google Analytics property settings" />
</x-admin.form-section>

<x-admin.form-section title="Facebook Pixel Code" icon="facebook">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Add your Facebook Pixel tracking code for conversion tracking and advertising.
    </p>

    <x-admin.textarea
        name="settings[tracking_facebook_pixel]"
        label="Facebook Pixel Code"
        value="{{ $settings['tracking_facebook_pixel']->value ?? '' }}"
        placeholder="Paste your Facebook Pixel code here"
        rows="5"
        hint="Find this code in your Facebook Pixel settings" />
</x-admin.form-section>

<x-admin.form-section title="Custom Head Code" icon="code-braces">
    <p class="admin-field-desc" style="margin-bottom: var(--space-lg);">
        Add any custom code that should be injected into the `&lt;head&gt;` tag of your storefront.
        Useful for additional tracking codes, meta tags, or custom scripts.
    </p>

    <x-admin.textarea
        name="settings[tracking_custom_head]"
        label="Custom Head Code"
        value="{{ $settings['tracking_custom_head']->value ?? '' }}"
        placeholder="Paste any additional code you want in the &lt;head&gt; tag here"
        rows="6"
        hint="Include full &lt;script&gt; or &lt;meta&gt; tags as needed. This code will be placed before &lt;/head&gt;." />
</x-admin.form-section>

<x-admin.form-section title="Tracking Code Status" icon="circle-check">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-lg);">
        <div style="padding: var(--space-lg); background: {{ (!empty($settings['tracking_google_pixel']->value ?? null)) ? '#ecfdf5' : '#f3f4f6' }}; border-radius: 8px; border-left: 3px solid {{ (!empty($settings['tracking_google_pixel']->value ?? null)) ? 'var(--wp-success)' : 'var(--wp-border)' }};">
            <div style="font-size: 13px; font-weight: 600; color: var(--wp-text); margin-bottom: 4px;">Google Pixel</div>
            <div style="font-size: 12px; color: var(--wp-muted);">
                {{ (!empty($settings['tracking_google_pixel']->value ?? null)) ? '✓ Configured' : 'Not configured' }}
            </div>
        </div>

        <div style="padding: var(--space-lg); background: {{ (!empty($settings['tracking_google_analytics']->value ?? null)) ? '#ecfdf5' : '#f3f4f6' }}; border-radius: 8px; border-left: 3px solid {{ (!empty($settings['tracking_google_analytics']->value ?? null)) ? 'var(--wp-success)' : 'var(--wp-border)' }};">
            <div style="font-size: 13px; font-weight: 600; color: var(--wp-text); margin-bottom: 4px;">Google Analytics</div>
            <div style="font-size: 12px; color: var(--wp-muted);">
                {{ (!empty($settings['tracking_google_analytics']->value ?? null)) ? '✓ Configured' : 'Not configured' }}
            </div>
        </div>

        <div style="padding: var(--space-lg); background: {{ (!empty($settings['tracking_facebook_pixel']->value ?? null)) ? '#ecfdf5' : '#f3f4f6' }}; border-radius: 8px; border-left: 3px solid {{ (!empty($settings['tracking_facebook_pixel']->value ?? null)) ? 'var(--wp-success)' : 'var(--wp-border)' }};">
            <div style="font-size: 13px; font-weight: 600; color: var(--wp-text); margin-bottom: 4px;">Facebook Pixel</div>
            <div style="font-size: 12px; color: var(--wp-muted);">
                {{ (!empty($settings['tracking_facebook_pixel']->value ?? null)) ? '✓ Configured' : 'Not configured' }}
            </div>
        </div>

        <div style="padding: var(--space-lg); background: {{ (!empty($settings['tracking_custom_head']->value ?? null)) ? '#ecfdf5' : '#f3f4f6' }}; border-radius: 8px; border-left: 3px solid {{ (!empty($settings['tracking_custom_head']->value ?? null)) ? 'var(--wp-success)' : 'var(--wp-border)' }};">
            <div style="font-size: 13px; font-weight: 600; color: var(--wp-text); margin-bottom: 4px;">Custom Head Code</div>
            <div style="font-size: 12px; color: var(--wp-muted);">
                {{ (!empty($settings['tracking_custom_head']->value ?? null)) ? '✓ Configured' : 'Not configured' }}
            </div>
        </div>
    </div>

    <p class="admin-field-desc" style="margin-top: var(--space-lg); font-style: italic; background: #fef3c7; padding: var(--space-md); border-radius: 4px;">
        ⚠️ <strong>Important:</strong> These codes will be injected directly into your storefront's `&lt;head&gt;` tag. Only add code from trusted sources. Verify the code is correct before saving.
    </p>
</x-admin.form-section>