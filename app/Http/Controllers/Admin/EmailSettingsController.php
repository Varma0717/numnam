<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use App\Services\EmailSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailSettingsController extends Controller
{
    public function __construct(private readonly EmailSettingsService $emailSettingsService) {}

    public function index(): View
    {
        $settings = $this->emailSettingsService->current();

        return view('admin.settings.email-tab', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mail_driver' => ['required', 'string', 'in:smtp,sendmail,mailgun,ses,postmark,resend'],
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'between:1,65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'in:tls,ssl'],
            'from_name' => ['required', 'string', 'max:255'],
            'from_address' => ['required', 'email', 'max:255'],
            'to_name' => ['nullable', 'string', 'max:255'],
            'to_address' => ['required', 'email', 'max:255'],
            'reply_to_name' => ['nullable', 'string', 'max:255'],
            'reply_to_address' => ['nullable', 'email', 'max:255'],
        ]);

        $setting = EmailSetting::query()->first();

        if ($setting) {
            if (! filled($data['mail_password'] ?? null)) {
                $data['mail_password'] = $setting->mail_password;
            }

            $setting->fill($data)->save();
        } else {
            EmailSetting::query()->create($data);
        }

        return back()->with('success', 'Email settings saved successfully.');
    }

    public function sendTest(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_to_address' => ['nullable', 'email', 'max:255'],
        ]);

        $settings = $this->emailSettingsService->applyToRuntime();
        $recipient = $data['test_to_address'] ?? $settings->to_address;

        if (! $recipient) {
            return back()->withErrors([
                'test_to_address' => 'Please save a To Address or enter a test recipient.',
            ]);
        }

        Mail::mailer($settings->mail_driver ?: 'smtp')->raw(
            "This is a test email from the admin email settings page.\n\nIf you received this, the SMTP configuration, from address, and recipient routing are working.",
            function ($message) use ($settings, $recipient): void {
                $message->to($recipient)
                    ->subject('Test Email from Admin Settings');

                if ($settings->from_address) {
                    $message->from($settings->from_address, $settings->from_name ?: config('app.name'));
                }

                if ($settings->reply_to_address) {
                    $message->replyTo($settings->reply_to_address, $settings->reply_to_name ?: null);
                }
            }
        );

        return back()->with('success', 'Test email sent successfully to ' . $recipient . '.');
    }
}
