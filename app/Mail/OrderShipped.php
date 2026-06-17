<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Order $order,
        public array $shipment = []
    ) {}

    public function build(): self
    {
        return $this->subject('📦 Your NumNam Order Has Shipped - #' . $this->order->order_number)
            ->view('emails.order-shipped', [
                'order' => $this->order,
                'shipment' => (object) $this->shipment,
            ]);
    }
}
