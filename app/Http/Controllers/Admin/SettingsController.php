<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $tabs = ['general', 'payment', 'shipping', 'tax', 'email', 'tracking'];

    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'general');
        if (!in_array($activeTab, $this->tabs, true)) {
            $activeTab = 'general';
        }

        $settings = SiteSetting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings', 'activeTab'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('_tab', 'general');

        if ($tab === 'email') {
            $request->validate([
                'settings' => 'required|array',
                'settings.email_from_name' => 'nullable|string|max:255',
                'settings.email_from_address' => 'nullable|email|max:255',
                'settings.email_admin_to_address' => 'nullable|email|max:255',
                'settings.email_test_to_address' => 'nullable|email|max:255',
                'settings.smtp_host' => 'nullable|string|max:255',
                'settings.smtp_port' => 'nullable|integer|min:1|max:65535',
                'settings.smtp_encryption' => 'nullable|in:tls,ssl,null',
                'settings.smtp_username' => 'nullable|string|max:255',
                'settings.smtp_password' => 'nullable|string|max:255',
                'settings.email_order_confirmation_enabled' => 'nullable|in:0,1',
                'settings.email_order_shipped_enabled' => 'nullable|in:0,1',
                'settings.email_order_delivered_enabled' => 'nullable|in:0,1',
                'settings.email_admin_new_order_enabled' => 'nullable|in:0,1',
            ]);
        } else {
            $request->validate([
                'settings'   => 'required|array',
                'settings.*' => 'nullable|string|max:5000',
            ]);
        }

        $settingsInput = $request->input('settings', []);

        // Keep the existing SMTP password when admin leaves it empty.
        if ($tab === 'email' && array_key_exists('smtp_password', $settingsInput) && $settingsInput['smtp_password'] === '') {
            unset($settingsInput['smtp_password']);
        }

        foreach ($settingsInput as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }

        return redirect()
            ->route('admin.settings.index', ['tab' => $tab])
            ->with('status', ucfirst($tab) . ' settings saved.');
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key'       => 'required|string|max:120|unique:site_settings,key',
            'value'     => 'nullable|string|max:2000',
            'type'      => 'required|in:text,textarea,boolean,number',
            'group'     => 'nullable|string|max:60',
            'is_public' => 'nullable',
        ]);

        $data['is_public'] = $request->boolean('is_public');

        SiteSetting::create($data);

        return redirect()->route('admin.settings.index')->with('status', 'Setting created.');
    }

    public function destroy(SiteSetting $setting)
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('status', 'Setting deleted.');
    }

    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_to' => 'required|email|max:255',
        ]);

        $settings = SiteSetting::query()
            ->whereIn('key', [
                'smtp_host',
                'smtp_port',
                'smtp_encryption',
                'smtp_username',
                'smtp_password',
                'email_from_name',
                'email_from_address',
            ])
            ->pluck('value', 'key');

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', (string) ($settings['smtp_host'] ?? config('mail.mailers.smtp.host')));
        Config::set('mail.mailers.smtp.port', (int) ($settings['smtp_port'] ?? config('mail.mailers.smtp.port')));
        Config::set('mail.mailers.smtp.encryption', ($settings['smtp_encryption'] ?? null) === 'null'
            ? null
            : ($settings['smtp_encryption'] ?? config('mail.mailers.smtp.encryption')));
        Config::set('mail.mailers.smtp.username', (string) ($settings['smtp_username'] ?? config('mail.mailers.smtp.username')));
        Config::set('mail.mailers.smtp.password', (string) ($settings['smtp_password'] ?? config('mail.mailers.smtp.password')));

        $fromAddress = (string) ($settings['email_from_address'] ?? config('mail.from.address'));
        $fromName = (string) ($settings['email_from_name'] ?? config('mail.from.name'));

        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.from.name', $fromName);

        // Rebuild mailers so runtime config changes are applied immediately.
        app('mail.manager')->forgetMailers();

        try {
            Mail::raw(
                'This is a test email from NumNam admin settings. SMTP and sender settings are working.',
                function ($message) use ($request, $fromAddress, $fromName): void {
                    $message
                        ->to($request->string('test_to')->value())
                        ->subject('NumNam SMTP Test Email');

                    if ($fromAddress !== '') {
                        $message->from($fromAddress, $fromName !== '' ? $fromName : null);
                    }
                }
            );
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.settings.index', ['tab' => 'email'])
                ->with('error', 'Test email failed: ' . $e->getMessage());
        }

        SiteSetting::updateOrCreate(
            ['key' => 'email_test_to_address'],
            ['value' => $request->string('test_to')->value()]
        );

        return redirect()
            ->route('admin.settings.index', ['tab' => 'email'])
            ->with('status', 'Test email sent successfully to ' . $request->string('test_to')->value());
    }
}
