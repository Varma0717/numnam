{{-- Settings > Email Tab --}}
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <input type="hidden" name="_tab" value="email">

    <section class="postbox">
        <div class="postbox-header">
            <h3>Sender Details</h3>
        </div>
        <div class="inside">
            <div class="admin-form-grid-2">
                <div class="admin-form-row">
                    <label for="email_from_name">From Name</label>
                    <input type="text" id="email_from_name" name="settings[email_from_name]" value="{{ $settings['email_from_name']->value ?? 'NumNam' }}" placeholder="NumNam">
                </div>
                <div class="admin-form-row">
                    <label for="email_from_address">From Email</label>
                    <input type="email" id="email_from_address" name="settings[email_from_address]" value="{{ $settings['email_from_address']->value ?? '' }}" placeholder="noreply@numnam.in">
                </div>
            </div>
        </div>
    </section>

    <section class="postbox">
        <div class="postbox-header">
            <h3>Order Notifications</h3>
        </div>
        <div class="inside">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Notification</th>
                        <th>Description</th>
                        <th style="width:80px; text-align:center;">Enabled</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Order Confirmation</strong></td>
                        <td>Sent to customer after placing an order</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_confirmation_enabled]" value="0">
                            <input type="checkbox" name="settings[email_order_confirmation_enabled]" value="1" {{ ($settings['email_order_confirmation_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Order Shipped</strong></td>
                        <td>Sent to customer when order status changes to shipped</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_shipped_enabled]" value="0">
                            <input type="checkbox" name="settings[email_order_shipped_enabled]" value="1" {{ ($settings['email_order_shipped_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Order Delivered</strong></td>
                        <td>Sent to customer when order is marked as delivered</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_order_delivered_enabled]" value="0">
                            <input type="checkbox" name="settings[email_order_delivered_enabled]" value="1" {{ ($settings['email_order_delivered_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>New Order (Admin)</strong></td>
                        <td>Sent to store admin when a new order is placed</td>
                        <td style="text-align:center;">
                            <input type="hidden" name="settings[email_admin_new_order_enabled]" value="0">
                            <input type="checkbox" name="settings[email_admin_new_order_enabled]" value="1" {{ ($settings['email_admin_new_order_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div style="margin-top:16px;">
        <button class="admin-btn" type="submit">Save Email Settings</button>
    </div>
</form>