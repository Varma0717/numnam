@component('mail::message')
# {{ $title }}

A subscription has been {{ $action }} in NumNam.

@component('mail::panel')
Customer
Name: {{ $subscription->user?->name ?? 'N/A' }}
Email: {{ $subscription->user?->email ?? 'N/A' }}
Subscription ID: #{{ $subscription->id }}
@endcomponent

@component('mail::panel')
Plan Details
Plan: {{ $subscription->plan_name }}
Type: {{ ucfirst($subscription->plan_type) }}
Duration: {{ $subscription->duration }}
Frequency: {{ ucfirst($subscription->frequency) }}
Status: {{ ucfirst($subscription->status) }}
@endcomponent

@if($subscription->next_billing_date)
Next Billing: {{ $subscription->next_billing_date->format('d M Y') }}
@endif

@if(!empty($reason) || !empty($retryCount))
@component('mail::panel')
Billing Diagnostics
@if(!empty($reason))
Reason: {{ $reason }}
@endif
@if(!empty($retryCount) && !empty($maxRetries))
Retry Attempt: {{ $retryCount }} / {{ $maxRetries }}
@endif
@endcomponent
@endif

@component('mail::button', ['url' => config('app.url')])
Open Admin Panel
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent