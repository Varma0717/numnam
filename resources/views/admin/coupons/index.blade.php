@extends('admin.layouts.app')

@section('title', 'Coupons - Admin')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.7rem;">
        <h3>Coupons</h3>
        <a class="admin-btn" href="{{ route('admin.coupons.create') }}">Create coupon</a>
    </div>

    <table class="admin-table">
        <thead>
        <tr><th>Code</th><th>Type</th><th>Value</th><th>Min Subtotal</th><th>Used</th><th>Active</th><th>Action</th></tr>
        </thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <td>{{ $coupon->code }}</td>
                <td>{{ strtoupper($coupon->type) }}</td>
                <td>{{ number_format($coupon->value, 2) }}</td>
                <td>{{ $coupon->min_subtotal ? number_format($coupon->min_subtotal, 2) : '-' }}</td>
                <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                <td>{{ $coupon->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a class="admin-link" href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button style="border:0;background:none;color:#b93434;cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No coupons found.</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $coupons->links() }}
</section>
@endsection
