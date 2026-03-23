@extends('admin.layouts.app')

@section('title', 'Subscriptions - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Subscriptions</h2>
    <p class="admin-desc">{{ $subscriptions->total() }} total subscriptions</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Plan</th>
            <th>Cycle</th>
            <th>Price</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody>
        @forelse($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->id }}</td>
                <td>{{ $subscription->user->name ?? '-' }}</td>
                <td><strong>{{ $subscription->plan_name }}</strong></td>
                <td>{{ ucfirst($subscription->frequency) }}</td>
                <td>Rs {{ number_format($subscription->price_per_cycle, 0) }}</td>
                <td>{{ ucfirst($subscription->status) }}</td>
                <td>{{ $subscription->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="7">No subscriptions found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $subscriptions->links() }}</div>
</section>
@endsection
