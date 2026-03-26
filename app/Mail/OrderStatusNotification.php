<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function build(): self
    {
        $statusLabels = [
            'processing' => 'Order Confirmed',
            'shipped' => 'Order Shipped',
            'delivered' => 'Order Delivered',
            'cancelled' => 'Order Cancelled',
            'refunded' => 'Refund Processed',
        ];

        $subject = ($statusLabels[$this->order->status] ?? 'Order Update')
            . ' — #' . $this->order->order_number;

        return $this->subject($subject)
            ->view('emails.order-status')
            ->with('order', $this->order);
    }
}
