<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function build(): self
    {
        return $this->subject('🔔 New Order #' . $this->order->order_number . ' - Action Required')
            ->view('emails.order-admin-notification', [
                'order' => $this->order,
            ]);
    }
}
