@extends('admin.layouts.app')

@section('title', 'Subscriptions - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Subscriptions</h2>
    <p class="admin-desc">{{ $subscriptions->total() }} total subscriptions</p>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by customer name...">
        <select name="status" style="width:auto; min-width:120px; border:1px solid #8c8f94; border-radius:4px; padding:0 8px; font-size:14px; line-height:2; min-height:30px;">
            <option value="">All Status</option>
            <option value="active" @selected(request('status')==='active' )>Active</option>
            <option value="paused" @selected(request('status')==='paused' )>Paused</option>
            <option value="cancelled" @selected(request('status')==='cancelled' )>Cancelled</option>
        </select>
        <button class="admin-btn" type="submit">Filter</button>
        @if(request('q') || request('status'))
        <a href="{{ route('admin.subscriptions.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Customer</th>
                    <th>Plan</th>
                    <th>Cycle</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Next Billing</th>
                    <th>Created</th>
                    <th style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $subscription)
                <tr>
                    <td>{{ $subscription->id }}</td>
                    <td>{{ $subscription->user->name ?? '—' }}</td>
                    <td><strong>{{ $subscription->plan_name }}</strong></td>
                    <td>{{ ucfirst($subscription->frequency) }}</td>
                    <td>₹{{ number_format($subscription->price_per_cycle, 0) }}</td>
                    <td><span class="status-badge status-badge--{{ $subscription->status }}">{{ ucfirst($subscription->status) }}</span></td>
                    <td>{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d M Y') : '—' }}</td>
                    <td>{{ $subscription->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="admin-link">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="admin-empty">No subscriptions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $subscriptions->links() }}</div>
</section>
@endsection