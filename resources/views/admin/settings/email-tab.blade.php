<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Email Settings</h4>
    </div>

    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.email-settings.update') }}" class="mb-4">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Mail Driver</label>
                    <select name="mail_driver" class="form-select" required>
                        @foreach (['smtp', 'sendmail', 'mailgun', 'ses', 'postmark', 'resend'] as $driver)
                        <option value="{{ $driver }}" @selected(old('mail_driver', $settings->mail_driver) === $driver)>{{ strtoupper($driver) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Host</label>
                    <input type="text" name="mail_host" class="form-control" value="{{ old('mail_host', $settings->mail_host) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Port</label>
                    <input type="number" name="mail_port" class="form-control" value="{{ old('mail_port', $settings->mail_port ?: 587) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" name="mail_username" class="form-control" value="{{ old('mail_username', $settings->mail_username) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">SMTP Password</label>
                    <input type="password" name="mail_password" class="form-control" value="{{ old('mail_password') }}" placeholder="Leave blank to keep current">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Encryption</label>
                    <select name="mail_encryption" class="form-select">
                        <option value="">None</option>
                        <option value="tls" @selected(old('mail_encryption', $settings->mail_encryption) === 'tls')>TLS</option>
                        <option value="ssl" @selected(old('mail_encryption', $settings->mail_encryption) === 'ssl')>SSL</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">From Name</label>
                    <input type="text" name="from_name" class="form-control" value="{{ old('from_name', $settings->from_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">From Address</label>
                    <input type="email" name="from_address" class="form-control" value="{{ old('from_address', $settings->from_address) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">To Name</label>
                    <input type="text" name="to_name" class="form-control" value="{{ old('to_name', $settings->to_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">To Address</label>
                    <input type="email" name="to_address" class="form-control" value="{{ old('to_address', $settings->to_address) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Reply To Name</label>
                    <input type="text" name="reply_to_name" class="form-control" value="{{ old('reply_to_name', $settings->reply_to_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Reply To Address</label>
                    <input type="email" name="reply_to_address" class="form-control" value="{{ old('reply_to_address', $settings->reply_to_address) }}">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save Email Settings</button>
            </div>
        </form>

        <hr>

        <form method="POST" action="{{ route('admin.email-settings.test') }}" class="mt-4">
            @csrf

            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Test Recipient</label>
                    <input type="email" name="test_to_address" class="form-control" value="{{ old('test_to_address', $settings->to_address) }}" placeholder="{{ $settings->to_address ?: 'recipient@example.com' }}">
                    <small class="text-muted">Leave it as the saved To Address or override it for a one-time test.</small>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary w-100">Send Test Email</button>
                </div>
            </div>
        </form>
    </div>
</div>