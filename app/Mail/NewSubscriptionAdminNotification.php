<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSubscriptionAdminNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Subscription $subscription,
        public string $action,
        public ?string $reason = null,
        public ?int $retryCount = null,
        public ?int $maxRetries = null,
    ) {}

    public function build(): self
    {
        $subjectMap = [
            'created' => 'New Subscription Created',
            'paused' => 'Subscription Paused',
            'resumed' => 'Subscription Resumed',
            'cancelled' => 'Subscription Cancelled',
            'billing_retry_failed' => 'Subscription Billing Retry Failed',
            'billing_cancelled' => 'Subscription Auto-Cancelled After Billing Failures',
        ];

        $title = $subjectMap[$this->action] ?? 'Subscription Update';

        return $this->subject($title . ' - #' . $this->subscription->id)
            ->view('emails.new-subscription-admin', [
                'subscription' => $this->subscription,
                'action' => $this->action,
                'title' => $title,
                'reason' => $this->reason,
                'retryCount' => $this->retryCount,
                'maxRetries' => $this->maxRetries,
            ]);
    }
}
