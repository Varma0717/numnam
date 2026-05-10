@extends('admin.layouts.app')

@section('title', 'Review Moderation - NumNam Admin')

@section('content')
<section class="admin-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:.6rem; flex-wrap:wrap;">
        <h3 style="margin:0;">Product Reviews</h3>
        <p class="admin-desc" style="margin:0;">{{ $reviews->total() }} total reviews</p>
    </div>

    <form method="GET" class="admin-grid" style="grid-template-columns:repeat(4,minmax(0,1fr)); margin:.8rem 0;">
        <select name="status">
            <option value="">All status</option>
            @foreach(['pending', 'approved', 'rejected'] as $status)
            <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <select name="rating">
            <option value="">All ratings</option>
            @foreach([5, 4, 3, 2, 1] as $rating)
            <option value="{{ $rating }}" @selected((string) request('rating')===(string) $rating)>{{ $rating }} star</option>
            @endforeach
        </select>

        <input name="product" value="{{ request('product') }}" placeholder="Filter by product name or slug">

        <button class="admin-btn" type="submit">Apply Filters</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>
                        <strong>{{ $review->product?->name ?? '-' }}</strong>
                        @if($review->product?->slug)
                        <div class="admin-muted">/{{ $review->product->slug }}</div>
                        @endif
                    </td>
                    <td>
                        {{ $review->user?->name ?? '-' }}
                        <div class="admin-muted">{{ $review->user?->email ?? '' }}</div>
                    </td>
                    <td>{{ str_repeat('★', (int) $review->rating) }}</td>
                    <td style="max-width:360px;">
                        @if($review->title)
                        <strong>{{ $review->title }}</strong><br>
                        @endif
                        {{ \Illuminate\Support\Str::limit($review->body, 180) }}
                    </td>
                    <td>
                        <span class="status-badge status-badge--{{ $review->moderation_status ?? ($review->is_approved ? 'approved' : 'pending') }}">
                            {{ ucfirst($review->moderation_status ?? ($review->is_approved ? 'approved' : 'pending')) }}
                        </span>
                    </td>
                    <td>{{ optional($review->created_at)->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                            <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="admin-link" style="border:0; background:none; cursor:pointer;">Approve</button>
                            </form>

                            <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="admin-link" style="border:0; background:none; cursor:pointer; color:#8a6a00;">Reject</button>
                            </form>

                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-link" style="border:0; background:none; cursor:pointer; color:#c33;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="admin-empty">
                        <p>No reviews found for the selected filters.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">{{ $reviews->links() }}</div>
</section>
@endsection