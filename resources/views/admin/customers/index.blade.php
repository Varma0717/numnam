@extends('admin.layouts.app')

@section('title', 'Customers - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Customers</h2>
    <p class="admin-desc">{{ $customers->total() }} registered customers</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Referral Code</th>
            <th>Joined</th>
        </tr>
        </thead>
        <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td><strong>{{ $customer->name }}</strong></td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?: '-' }}</td>
                <td>{{ $customer->referral_code ?: '-' }}</td>
                <td>{{ $customer->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">No customers found.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $customers->links() }}</div>
</section>
@endsection
