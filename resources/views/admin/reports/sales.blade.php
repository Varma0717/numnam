@extends('admin.layouts.app')

@section('title', 'Sales Report - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Sales Report</h2>
    <p class="admin-desc">Revenue and order statistics.</p>
</div>

<form method="GET" class="admin-date-range">
    <label style="font-size:13px; font-weight:600;">From:</label>
    <input type="date" name="from" value="{{ $from }}">
    <label style="font-size:13px; font-weight:600;">To:</label>
    <input type="date" name="to" value="{{ $to }}">
    <button class="admin-btn" type="submit">Filter</button>
    <a href="{{ route('admin.reports.sales') }}" class="admin-btn-secondary" style="text-decoration:none;">Reset</a>
</form>

{{-- Summary Cards --}}
<div class="admin-grid metrics-grid" style="margin-bottom:20px;">
    <div class="metric-card">
        <strong>₹{{ number_format($totalRevenue, 0) }}</strong>
        <span>Total Revenue</span>
    </div>
    <div class="metric-card">
        <strong>{{ $totalOrders }}</strong>
        <span>Orders</span>
    </div>
    <div class="metric-card">
        <strong>₹{{ number_format($avgOrderValue, 0) }}</strong>
        <span>Avg Order Value</span>
    </div>
    <div class="metric-card">
        <strong>₹{{ number_format($totalTax, 0) }}</strong>
        <span>Tax Collected</span>
    </div>
</div>

{{-- Daily Sales Table --}}
<section class="admin-panel" style="margin-bottom:20px;">
    <h3 style="padding:8px 12px; margin:0; border-bottom:1px solid var(--wp-border);">Daily Sales</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Orders</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailySales as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                    <td>{{ $day->orders }}</td>
                    <td>₹{{ number_format($day->revenue, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="admin-empty">No sales data for this period.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

{{-- Top Products --}}
<section class="admin-panel">
    <h3 style="padding:8px 12px; margin:0; border-bottom:1px solid var(--wp-border);">Top Selling Products</h3>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty Sold</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $i => $product)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $product->name }}</strong></td>
                    <td>{{ $product->qty }}</td>
                    <td>₹{{ number_format($product->revenue, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="admin-empty">No product data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection