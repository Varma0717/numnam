{{-- Settings > Email Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="email">

    <section class="postbox">
        <div class="postbox-header">
            <h3>📧 Sender Details</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Email is sent via the server's built-in mail system (sendmail). No SMTP credentials are required.
                Only the <strong>From Name</strong>, <strong>From Email</strong>, and <strong>Admin To Address</strong> need to be set here.
            </p>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="email_from_name">From Name <span style="color:var(--wp-error)">*</span></label>
                    <input type="text" id="email_from_name" name="settings[email_from_name]" value="{{ $settings['email_from_name']->value ?? 'NumNam' }}" placeholder="NumNam">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Displayed as the sender name in all outgoing emails</p>
                </div>
                <div class="admin-form-row">
                    <label for="email_from_address">From Email <span style="color:var(--wp-error)">*</span></label>
                    <input type="email" id="email_from_address" name="settings[email_from_address]" value="{{ $settings['email_from_address']->value ?? 'noreply@numnam.com' }}" placeholder="noreply@numnam.com">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Must be an address on <strong>numnam.com</strong> for best deliverability with sendmail</p>
                </div>
                <div class="admin-form-row">
                    <label for="email_admin_to_address">Admin To Address <span style="color:var(--wp-error)">*</span></label>
                    <input type="email" id="email_admin_to_address" name="settings[email_admin_to_address]" value="{{ $settings['email_admin_to_address']->value ?? 'smikudoo@gmail.com' }}" placeholder="smikudoo@gmail.com">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Where new order alerts and contact form leads are delivered. Can be any email.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>🔔 Order Notifications</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Enable or disable automated email notifications for various order events.
            </p>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Notification</th>
                        <th>Description</th>
                        <th style="width:100px; text-align:center;">Enabled</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Order Confirmation</strong></td>
                        <td style="font-size: 13px; color: var(--wp-muted);">Sent to customer after placing an order</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_confirmation_enabled]" value="0">
                            <input type="checkbox" class="admin-toggle-checkbox" name="settings[email_order_confirmation_enabled]" value="1" {{ ($settings['email_order_confirmation_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Order Shipped</strong></td>
                        <td style="font-size: 13px; color: var(--wp-muted);">Sent to customer when order status changes to shipped</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_shipped_enabled]" value="0">
                            <input type="checkbox" class="admin-toggle-checkbox" name="settings[email_order_shipped_enabled]" value="1" {{ ($settings['email_order_shipped_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Order Delivered</strong></td>
                        <td style="font-size: 13px; color: var(--wp-muted);">Sent to customer when order is marked as delivered</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_delivered_enabled]" value="0">
                            <input type="checkbox" class="admin-toggle-checkbox" name="settings[email_order_delivered_enabled]" value="1" {{ ($settings['email_order_delivered_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>New Order (Admin)</strong></td>
                        <td style="font-size: 13px; color: var(--wp-muted);">Sent to store admin when a new order is placed</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_admin_new_order_enabled]" value="0">
                            <input type="checkbox" class="admin-toggle-checkbox" name="settings[email_admin_new_order_enabled]" value="1" {{ ($settings['email_admin_new_order_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>🧪 Test Email</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                Sends a real email using the From address and server sendmail above. Use this to confirm delivery is working.
            </p>

            <div class="admin-form-row">
                <label for="test_to">Test Recipient Email</label>
                <div style="display:flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <input type="email" id="test_to" name="test_to" value="{{ $settings['email_admin_to_address']->value ?? 'smikudoo@gmail.com' }}" placeholder="smikudoo@gmail.com" style="flex:1; min-width: 260px;">
                    <button class="admin-btn" type="submit" formaction="{{ route('admin.settings.email.test') }}" formmethod="POST">📨 Send Test Email</button>
                </div>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Defaults to the Admin To Address. Override here for a one-off test.</p>
            </div>
        </div>
    </section>

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save Email Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>