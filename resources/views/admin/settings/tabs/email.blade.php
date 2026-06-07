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
                Configure the sender name, sender email, recipient address, and SMTP credentials used for outgoing mail.
            </p>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="email_from_name">From Name</label>
                    <input type="text" id="email_from_name" name="settings[email_from_name]" value="{{ $settings['email_from_name']->value ?? 'NumNam' }}" placeholder="NumNam">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Displayed as sender name in emails</p>
                </div>
                <div class="admin-form-row">
                    <label for="email_from_address">From Email</label>
                    <input type="email" id="email_from_address" name="settings[email_from_address]" value="{{ $settings['email_from_address']->value ?? '' }}" placeholder="noreply@numnam.in">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Sender address used by SMTP</p>
                </div>
                <div class="admin-form-row">
                    <label for="email_admin_to_address">To Address</label>
                    <input type="email" id="email_admin_to_address" name="settings[email_admin_to_address]" value="{{ $settings['email_admin_to_address']->value ?? 'smikudoo@gmail.com' }}" placeholder="smikudoo@gmail.com">
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Default recipient for admin notifications and test mail</p>
                </div>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>📨 SMTP Settings</h3>
        </div>
        <div class="inside">
            <p style="color: var(--wp-muted); font-size: 13px; margin: 0 0 var(--space-lg); line-height: 1.5;">
                These values are saved in the database and applied at runtime, so you do not need to place them in <code>.env</code>.
            </p>

            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="smtp_host">SMTP Host</label>
                    <input type="text" id="smtp_host" name="settings[smtp_host]" value="{{ $settings['smtp_host']->value ?? '' }}" placeholder="smtp.example.com">
                </div>
                <div class="admin-form-row">
                    <label for="smtp_port">SMTP Port</label>
                    <input type="number" id="smtp_port" name="settings[smtp_port]" value="{{ $settings['smtp_port']->value ?? '587' }}" min="1" max="65535">
                </div>
                <div class="admin-form-row">
                    <label for="smtp_username">SMTP Username</label>
                    <input type="text" id="smtp_username" name="settings[smtp_username]" value="{{ $settings['smtp_username']->value ?? '' }}" placeholder="username@example.com">
                </div>
                <div class="admin-form-row">
                    <label for="smtp_password">SMTP Password</label>
                    <input type="password" id="smtp_password" name="settings[smtp_password]" value="" placeholder="Leave blank to keep current">
                </div>
                <div class="admin-form-row">
                    <label for="smtp_encryption">SMTP Encryption</label>
                    <select id="smtp_encryption" name="settings[smtp_encryption]">
                        <option value="" {{ ($settings['smtp_encryption']->value ?? '') === '' ? 'selected' : '' }}>None</option>
                        <option value="tls" {{ ($settings['smtp_encryption']->value ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['smtp_encryption']->value ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="null" {{ ($settings['smtp_encryption']->value ?? '') === 'null' ? 'selected' : '' }}>Null</option>
                    </select>
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
                Send a test email using the saved SMTP and sender settings to confirm delivery.
            </p>

            <div class="admin-form-row">
                <label for="test_to">Test Recipient Email</label>
                <div style="display:flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <input type="email" id="test_to" name="test_to" value="{{ $settings['email_test_to_address']->value ?? ($settings['email_admin_to_address']->value ?? 'smikudoo@gmail.com') }}" placeholder="smikudoo@gmail.com" style="flex:1; min-width: 260px;">
                    <button class="admin-btn" type="submit" formaction="{{ route('admin.settings.email.test') }}" formmethod="POST">📨 Send Test Email</button>
                </div>
                <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">This sends a real email to the address above using the saved SMTP configuration.</p>
            </div>
        </div>
    </section>

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save Email Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>