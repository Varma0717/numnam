@extends('admin.layouts.app')

@section('title', 'Subscription Plans')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:.6rem; flex-wrap:wrap;">
        <h3 style="margin:0;">Subscription Plans</h3>
        <a href="{{ route('admin.pricing-plans.create') }}" class="admin-btn" style="text-decoration:none;">Add Plan</a>
    </div>

    <form method="GET" class="admin-search-bar" style="padding:10px 0;">
        <input name="q" value="{{ request('q') }}" placeholder="Search name, slug, or description">
        <select name="status">
            <option value="">All statuses</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <button class="admin-btn" type="submit">Filter</button>
        @if(request('q') || request('status'))
        <a href="{{ route('admin.pricing-plans.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Billing</th>
                <th>Duration</th>
                <th>Sort</th>
                <th>Products</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
            <tr>
                <td>
                    <strong>{{ $plan->name }}</strong>
                    <div class="admin-muted" style="margin-top:2px;">/{{ $plan->slug }}</div>
                </td>
                <td>Rs {{ number_format((float) $plan->price, 0) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $plan->billing_cycle ?? 'monthly')) }}</td>
                <td>{{ $plan->duration ?: '-' }}</td>
                <td>{{ $plan->sort_order }}</td>
                <td>{{ $plan->products_count }}</td>
                <td>
                    <span class="status-badge status-badge--{{ $plan->is_active ? 'active' : 'pending' }}">
                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.pricing-plans.edit', $plan) }}" class="admin-link">Edit</a>
                    <span style="color:var(--wp-border); margin:0 4px;">|</span>
                    <form method="POST" action="{{ route('admin.pricing-plans.destroy', $plan) }}" style="display:inline;" onsubmit="return confirm('Delete this subscription plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="admin-empty">No subscription plans found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:.75rem;">{{ $plans->appends(request()->query())->links() }}</div>
</section>
@endsection
