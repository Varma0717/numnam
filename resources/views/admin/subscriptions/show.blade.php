@extends('admin.layouts.app')

@section('title', 'Subscription #' . $subscription->id . ' - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Subscription #{{ $subscription->id }}</h2>
        <p class="admin-desc">{{ $subscription->plan_name }} &middot; {{ $subscription->user->name ?? 'Unknown' }}</p>
    </div>
    <a href="{{ route('admin.subscriptions.index') }}" class="admin-btn-secondary" style="text-decoration:none;">&larr; Back</a>
</div>

<div class="admin-grid" style="grid-template-columns:2fr 1fr; gap:20px;">
    {{-- Details Panel --}}
    <section class="admin-panel" style="padding:20px;">
        <div style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px;">
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Customer</label>
                <p style="margin:4px 0 0; font-size:14px;">
                    <a href="{{ route('admin.customers.show', $subscription->user) }}" class="admin-link">{{ $subscription->user->name ?? '—' }}</a>
                </p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Plan</label>
                <p style="margin:4px 0 0; font-size:14px;">{{ $subscription->plan_name }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Frequency</label>
                <p style="margin:4px 0 0; font-size:14px;">{{ ucfirst($subscription->frequency) }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Price / Cycle</label>
                <p style="margin:4px 0 0; font-size:14px;">₹{{ number_format($subscription->price_per_cycle, 0) }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Next Billing</label>
                <p style="margin:4px 0 0; font-size:14px;">{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d M Y') : '—' }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Ends At</label>
                <p style="margin:4px 0 0; font-size:14px;">{{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : '—' }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Created</label>
                <p style="margin:4px 0 0; font-size:14px;">{{ $subscription->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Status</label>
                <p style="margin:4px 0 0;"><span class="status-badge status-badge--{{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span></p>
            </div>
        </div>
    </section>

    {{-- Update Status --}}
    <section class="admin-panel">
        <h3 style="padding:10px 12px; margin:0; border-bottom:1px solid var(--wp-border); font-size:14px; font-weight:600;">Update Status</h3>
        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}" style="padding:16px;">
            @csrf
            @method('PUT')
            <div class="admin-form-row">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" @selected($subscription->status === 'active')>Active</option>
                    <option value="paused" @selected($subscription->status === 'paused')>Paused</option>
                    <option value="cancelled" @selected($subscription->status === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <button class="admin-btn" type="submit">Save</button>
        </form>
    </section>
</div>
@endsection