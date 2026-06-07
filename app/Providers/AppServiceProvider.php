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
                'email_test_to_address',
            ])
            ->pluck('value', 'key');

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', (string) ($settings['smtp_host'] ?? config('mail.mailers.smtp.host')));
        Config::set('mail.mailers.smtp.port', (int) ($settings['smtp_port'] ?? config('mail.mailers.smtp.port')));

        $encryption = $settings['smtp_encryption'] ?? config('mail.mailers.smtp.encryption');
        Config::set('mail.mailers.smtp.encryption', $encryption === 'null' ? null : $encryption);
        Config::set('mail.mailers.smtp.username', (string) ($settings['smtp_username'] ?? config('mail.mailers.smtp.username')));
        Config::set('mail.mailers.smtp.password', (string) ($settings['smtp_password'] ?? config('mail.mailers.smtp.password')));

        $fromAddress = (string) ($settings['email_from_address'] ?? config('mail.from.address'));
        $fromName = (string) ($settings['email_from_name'] ?? config('mail.from.name'));

        Config::set('mail.from.address', $fromAddress);
        Config::set('mail.from.name', $fromName);

        Config::set('mail.contact_recipient', (string) ($settings['email_admin_to_address'] ?? config('mail.contact_recipient')));
        Config::set('mail.order_recipient', (string) ($settings['email_admin_to_address'] ?? config('mail.order_recipient')));

        if (function_exists('app') && app()->bound('mail.manager')) {
            app('mail.manager')->forgetMailers();
        }
    }
}
