@extends('admin.layouts.app')

@section('title', 'Customers - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Customers</h2>
    <p class="admin-desc">{{ $customers->total() }} registered customers</p>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or email...">
        <button class="admin-btn" type="submit">Search</button>
        @if(request('q'))
        <a href="{{ route('admin.customers.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Orders</th>
                    <th>Referral Code</th>
                    <th>Joined</th>
                    <th style="width:80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td><strong>{{ $customer->name }}</strong></td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->orders_count }}</td>
                    <td>{{ $customer->referral_code ?: '—' }}</td>
                    <td>{{ $customer->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="admin-link">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="admin-empty">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $customers->links() }}</div>
</section>
@endsection