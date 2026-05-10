@component('mail::message')
# {{ $title }}

<p>Hello {{ $subscription->user?->name ?? 'Customer' }},</p>

<p>Your NumNam subscription has been <strong>{{ $action }}</strong>.</p>

<p>
    <strong>Plan:</strong> {{ $subscription->plan_name }}<br>
    <strong>Type:</strong> {{ ucfirst($subscription->plan_type) }}<br>
    <strong>Duration:</strong> {{ $subscription->duration }}<br>
    <strong>Frequency:</strong> {{ ucfirst($subscription->frequency) }}<br>
    <strong>Status:</strong> {{ ucfirst($subscription->status) }}
</p>

@if($subscription->next_billing_date)
<p>
    <strong>Next billing date:</strong> {{ $subscription->next_billing_date->format('d M Y') }}
</p>
@endif

<p>
    You can manage your subscription anytime from your account dashboard on numnam.com.
</p>

@if(!empty($reason) || !empty($retryCount))
@component('mail::panel')
Billing Details
@if(!empty($reason))
Reason: {{ $reason }}
@endif
@if(!empty($retryCount) && !empty($maxRetries))
Retry Attempt: {{ $retryCount }} / {{ $maxRetries }}
@endif
@endcomponent
@endif

Thanks,
{{ config('app.name') }}
@endcomponent