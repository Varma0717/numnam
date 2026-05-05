@component('mail::message')
# New Order Received

A new order has been placed: **#{{ $order->order_number }}**

@component('mail::panel')
Customer
Name: {{ $order->user?->name ?? $order->ship_name }}
Email: {{ $order->user?->email ?? 'N/A' }}
Phone: {{ $order->ship_phone }}
@endcomponent

@component('mail::panel')
Shipping Address
{{ $order->ship_name }}
{{ $order->ship_address }}
{{ $order->ship_city }}, {{ $order->ship_state }} - {{ $order->ship_pincode }}
@endcomponent

Payment
Method: {{ strtoupper($order->payment_gateway ?: $order->payment_method ?: 'N/A') }}
Status: {{ strtoupper($order->payment_status ?: 'PENDING') }}

@component('mail::table')
| Item | Qty | Amount |
|:-----|:---:|-------:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | Rs {{ number_format($item->line_total, 2) }} |
@endforeach
@endcomponent

**Order Total:** Rs {{ number_format($order->total, 2) }}

@component('mail::button', ['url' => route('admin.orders.show', $order)])
Open Order In Admin
@endcomponent

@endcomponent