@component('mail::message')
# Thanks for your order, {{ $order->user?->name ?? 'Customer' }}

Your order **#{{ $order->order_number }}** has been placed successfully.

**Payment Method:** {{ strtoupper($order->payment_gateway ?: $order->payment_method ?: 'N/A') }}
**Payment Status:** {{ strtoupper($order->payment_status ?: 'PENDING') }}

@component('mail::panel')
Shipping To
{{ $order->ship_name }}
{{ $order->ship_phone }}
{{ $order->ship_address }}
{{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
@endcomponent

@component('mail::table')
| Item | Qty | Amount |
|:-----|:---:|-------:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | Rs {{ number_format($item->line_total, 2) }} |
@endforeach
@endcomponent

**Order Total:** Rs {{ number_format($order->total, 2) }}

@component('mail::button', ['url' => route('store.account')])
View My Orders
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent