@component('mail::message')
# Order {{ ucfirst($order->status) }}

Hi {{ $order->user?->name ?? 'Customer' }},

Your order **#{{ $order->order_number }}** has been updated to: **{{ ucfirst($order->status) }}**.

@if($order->status === 'processing')
We're preparing your order for dispatch. You'll receive tracking details once shipped.
@elseif($order->status === 'shipped')
Your order is on its way! @if($order->tracking_number) Tracking: **{{ $order->tracking_number }}** @endif
@elseif($order->status === 'delivered')
Your order has been delivered. We hope your little one enjoys it!
@elseif($order->status === 'cancelled')
Your order has been cancelled. If you didn't request this, please contact support.
@elseif($order->status === 'refunded')
A refund of ₹{{ number_format($order->total, 2) }} has been initiated to your original payment method.
@endif

**Order Total:** ₹{{ number_format($order->total, 2) }}

@component('mail::button', ['url' => url('/account')])
View Your Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent