<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMailSettings();

        View::composer('store.*', function ($view) {
            $user = auth()->user();

            if ($user) {
                $cartItemCount = (int) CartItem::query()
                    ->where('user_id', $user->id)
                    ->sum('qty');
            } else {
                $cartItemCount = collect(session('cart', []))
                    ->sum(function ($line) {
                        return (int) ($line['qty'] ?? 0);
                    });
            }

            $view->with('cartItemCount', $cartItemCount);
        });
    }

    private function bootMailSettings(): void
    {
        $settings = SiteSetting::query()
            ->whereIn('key', [
                'smtp_host',
                'smtp_port',
                'smtp_encryption',
                'smtp_username',
                'smtp_password',
                'email_from_name',
                'email_from_address',
                'email_admin_to_address',
            ])
            ->pluck('value', 'key');

        // Only apply DB-driven SMTP overrides when an smtp_host is actually saved
        // and the base mailer is smtp. Never force smtp over sendmail/.env choice.
        $dbHost = filled($settings['smtp_host'] ?? null) ? (string) $settings['smtp_host'] : null;
        $baseMailer = config('mail.default', 'sendmail');

        if ($dbHost !== null && $baseMailer === 'smtp') {
            Config::set('mail.mailers.smtp.transport', 'smtp');
            Config::set('mail.mailers.smtp.host', $dbHost);
            Config::set('mail.mailers.smtp.port', (int) ($settings['smtp_port'] ?? config('mail.mailers.smtp.port')));

            $encryption = $settings['smtp_encryption'] ?? config('mail.mailers.smtp.encryption');
            Config::set('mail.mailers.smtp.encryption', $encryption === 'null' ? null : $encryption);
            Config::set('mail.mailers.smtp.username', (string) ($settings['smtp_username'] ?? config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', (string) ($settings['smtp_password'] ?? config('mail.mailers.smtp.password')));

            if (app()->bound('mail.manager')) {
                app('mail.manager')->forgetMailers();
            }
        }

        // From address and recipients are always applied regardless of mailer type.
        if (filled($settings['email_from_address'] ?? null)) {
            Config::set('mail.from.address', (string) $settings['email_from_address']);
        }

        if (filled($settings['email_from_name'] ?? null)) {
            Config::set('mail.from.name', (string) $settings['email_from_name']);
        }

        if (filled($settings['email_admin_to_address'] ?? null)) {
            Config::set('mail.contact_recipient', (string) $settings['email_admin_to_address']);
            Config::set('mail.order_recipient', (string) $settings['email_admin_to_address']);
        }
    }
}
