@extends('admin.layouts.app')

@section('title', 'Coupons - Admin')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.7rem;">
        <h3>Coupons</h3>
        <a class="admin-btn" href="{{ route('admin.coupons.create') }}">Create coupon</a>
    </div>

    <form method="POST" action="{{ Route::has('admin.coupons.bulk') ? route('admin.coupons.bulk') : '#' }}" id="bulk-form">
        @csrf
        <div class="admin-bulk-bar">
            <select name="bulk_action">
                <option value="">Bulk Actions</option>
                <option value="activate">Activate</option>
                <option value="deactivate">Deactivate</option>
                <option value="delete">Delete</option>
            </select>
            <button class="admin-btn" type="submit" onclick="return confirm('Apply bulk action to selected coupons?')">Apply</button>
            <span class="admin-muted" id="bulk-count"></span>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th class="check-column"><input type="checkbox" id="select-all"></th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Min Subtotal</th>
                    <th>Used</th>
                    <th>Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td class="check-column"><input type="checkbox" name="ids[]" value="{{ $coupon->id }}"></td>
                    <td><strong>{{ $coupon->code }}</strong></td>
                    <td>{{ strtoupper($coupon->type) }}</td>
                    <td>{{ number_format($coupon->value, 2) }}</td>
                    <td>{{ $coupon->min_subtotal ? number_format($coupon->min_subtotal, 2) : '-' }}</td>
                    <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}</td>
                    <td>
                        <span class="status-badge status-badge--{{ $coupon->is_active ? 'active' : 'paused' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a class="admin-link" href="{{ route('admin.coupons.edit', $coupon) }}">Edit</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display:inline;" onsubmit="return confirm('Delete this coupon?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="admin-empty">No coupons found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </form>

    {{ $coupons->links() }}
</section>
@endsection