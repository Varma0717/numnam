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
                Configure the sender name and email address for all outgoing store emails.
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
                    <p style="font-size: 12px; color: var(--wp-muted); margin-top: 6px;">Reply-to address for customer emails</p>
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

    <div style="margin-top: var(--space-2xl); display: flex; gap: var(--space-lg); align-items: center;">
        <button class="admin-btn" type="submit">💾 Save Email Settings</button>
        <span style="color: var(--wp-muted); font-size: 12px;">Changes will be applied immediately</span>
    </div>
</form>