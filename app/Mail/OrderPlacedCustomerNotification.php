<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedCustomerNotification extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function build(): self
    {
        return $this->subject('✓ Order Confirmed - #' . $this->order->order_number)
            ->view('emails.order-placed', [
                'order' => $this->order,
            ]);
    }
}
