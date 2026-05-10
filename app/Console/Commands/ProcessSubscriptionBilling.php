<?php

namespace App\Console\Commands;

use App\Mail\NewSubscriptionAdminNotification;
use App\Mail\SubscriptionStatusNotification;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ProcessSubscriptionBilling extends Command
{
    protected $signature = 'subscriptions:process-billing {--max-retries=3 : Maximum failed retries before cancellation}';

    protected $description = 'Process due subscription billing cycles and apply retry/cancellation rules.';

    public function handle(): int
    {
        $maxRetries = max(1, (int) $this->option('max-retries'));

        $dueSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('next_billing_date')
            ->whereDate('next_billing_date', '<=', now()->toDateString())
            ->with('user:id,name,email')
            ->get();

        if ($dueSubscriptions->isEmpty()) {
            $this->info('No due subscriptions found.');

            return self::SUCCESS;
        }

        $processed = 0;
        $successful = 0;
        $failed = 0;
        $cancelled = 0;

        foreach ($dueSubscriptions as $subscription) {
            $processed++;

            [$ok, $reason] = $this->attemptBilling($subscription);

            if ($ok) {
                $successful++;
                continue;
            }

            $failed++;
            $retryCount = ((int) $subscription->billing_retry_count) + 1;

            $subscription->billing_retry_count = $retryCount;
            $subscription->last_billing_attempt_at = now();
            $subscription->last_billing_error = $reason;

            if ($retryCount >= $maxRetries) {
                $subscription->status = 'cancelled';
                $cancelled++;
            }

            $subscription->save();

            $this->notifyBillingFailure($subscription, $reason, $retryCount, $maxRetries);

            $this->warn(sprintf(
                'Subscription #%d billing failed (%s). Retry %d/%d%s',
                $subscription->id,
                $reason,
                $retryCount,
                $maxRetries,
                $retryCount >= $maxRetries ? ' - cancelled' : ''
            ));
        }

        $this->info(sprintf(
            'Subscription billing run complete. Processed: %d, success: %d, failed: %d, cancelled: %d',
            $processed,
            $successful,
            $failed,
            $cancelled
        ));

        return self::SUCCESS;
    }

    private function attemptBilling(Subscription $subscription): array
    {
        if (! $subscription->user) {
            return [false, 'user_missing'];
        }

        if ((float) $subscription->price_per_cycle <= 0) {
            return [false, 'invalid_price_per_cycle'];
        }

        $nextDate = match ((string) $subscription->frequency) {
            'weekly' => now()->addWeek()->startOfDay(),
            'monthly' => now()->addMonth()->startOfDay(),
            default => null,
        };

        if (! $nextDate) {
            return [false, 'unsupported_frequency'];
        }

        $subscription->forceFill([
            'next_billing_date' => $nextDate->toDateString(),
            'billing_retry_count' => 0,
            'last_billing_attempt_at' => now(),
            'last_billing_error' => null,
        ])->save();

        $this->line(sprintf(
            'Subscription #%d billed successfully. Next billing date set to %s.',
            $subscription->id,
            $nextDate->toDateString()
        ));

        return [true, null];
    }

    private function notifyBillingFailure(
        Subscription $subscription,
        string $reason,
        int $retryCount,
        int $maxRetries
    ): void {
        $subscription->loadMissing('user');

        $action = $subscription->status === 'cancelled'
            ? 'billing_cancelled'
            : 'billing_retry_failed';

        if ($subscription->user?->email) {
            try {
                Mail::to($subscription->user->email)->queue(
                    new SubscriptionStatusNotification(
                        $subscription->fresh()->loadMissing('user'),
                        $action,
                        $reason,
                        $retryCount,
                        $maxRetries
                    )
                );
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        $adminRecipient = (string) config('mail.order_recipient', '');
        if ($adminRecipient !== '') {
            try {
                Mail::to($adminRecipient)->queue(
                    new NewSubscriptionAdminNotification(
                        $subscription->fresh()->loadMissing('user'),
                        $action,
                        $reason,
                        $retryCount,
                        $maxRetries
                    )
                );
            } catch (\Throwable $exception) {
                report($exception);
            }
        }
    }
}
