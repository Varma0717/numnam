@extends('admin.layouts.app')

@section('title', 'Customers Report - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Customers Report</h2>
    <p class="admin-desc">Customer registration and spending overview.</p>
</div>

<form method="GET" class="admin-date-range">
    <label style="font-size:13px; font-weight:600;">From:</label>
    <input type="date" name="from" value="{{ $from }}">
    <label style="font-size:13px; font-weight:600;">To:</label>
    <input type="date" name="to" value="{{ $to }}">
    <button class="admin-btn" type="submit">Filter</button>
    <a href="{{ route('admin.reports.customers') }}" class="admin-btn-secondary" style="text-decoration:none;">Reset</a>
</form>

<div class="admin-grid metrics-grid" style="margin-bottom:20px;">
    <div class="metric-card">
        <strong>{{ $newCustomers }}</strong>
        <span>New Customers (period)</span>
    </div>
    <div class="metric-card">
        <strong>{{ $totalCustomers }}</strong>
        <span>Total Customers</span>
    </div>
</div>

<section class="admin-panel">
    <h3 style="padding:8px 12px; margin:0; border-bottom:1px solid var(--wp-border);">Top Spenders</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Orders</th>
                    <th>Total Spent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topSpenders as $i => $spender)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $spender->name }}</strong></td>
                    <td>{{ $spender->email }}</td>
                    <td>{{ $spender->orders }}</td>
                    <td>₹{{ number_format($spender->spent, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="admin-empty">No customer data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection