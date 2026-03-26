<?php

namespace App\Observers;

use App\Mail\OrderStatusNotification;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if ($order->wasChanged('status') && $order->user) {
            try {
                Mail::to($order->user->email)->queue(
                    new OrderStatusNotification($order)
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
