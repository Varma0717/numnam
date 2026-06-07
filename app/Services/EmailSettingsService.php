<?php

namespace App\Services;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailSettingsService
{
    public function current(): EmailSetting
    {
        return EmailSetting::query()->first() ?? new EmailSetting([
            'mail_driver' => 'smtp',
            'mail_host' => '',
            'mail_port' => 587,
            'mail_username' => '',
            'mail_password' => '',
            'mail_encryption' => 'tls',
            'from_name' => config('app.name'),
            'from_address' => '',
            'to_name' => '',
            'to_address' => '',
            'reply_to_name' => '',
            'reply_to_address' => '',
        ]);
    }

    public function applyToRuntime(?EmailSetting $setting = null): EmailSetting
    {
        $setting = $setting ?? $this->current();

        Config::set('mail.default', $setting->mail_driver ?: 'smtp');
        Config::set('mail.from.address', $setting->from_address ?: config('mail.from.address'));
        Config::set('mail.from.name', $setting->from_name ?: config('mail.from.name'));

        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $setting->mail_host);
        Config::set('mail.mailers.smtp.port', (int) $setting->mail_port ?: 587);
        Config::set('mail.mailers.smtp.username', $setting->mail_username);
        Config::set('mail.mailers.smtp.password', $setting->mail_password);
        Config::set('mail.mailers.smtp.encryption', $setting->mail_encryption ?: null);

        Mail::purge($setting->mail_driver ?: 'smtp');

        return $setting;
    }
}
