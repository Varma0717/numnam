<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewModerationController extends Controller
{
    public function index(Request $request)
    {
        $reviews = ProductReview::query()
            ->with(['user:id,name,email', 'product:id,name,slug'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status')->toString();
                if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
                    $query->where('moderation_status', $status);
                }
            })
            ->when($request->filled('rating'), fn($query) => $query->where('rating', (int) $request->input('rating')))
            ->when($request->filled('product'), function ($query) use ($request) {
                $needle = trim((string) $request->input('product'));
                if ($needle === '') {
                    return;
                }

                $query->whereHas('product', function ($productQuery) use ($needle) {
                    $productQuery->where('name', 'like', '%' . $needle . '%')
                        ->orWhere('slug', 'like', '%' . $needle . '%');
                });
            })
            ->latest('id')
            ->paginate(25)
            ->appends($request->query());

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update([
            'is_approved' => true,
            'moderation_status' => 'approved',
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review approved successfully.');
    }

    public function reject(ProductReview $review): RedirectResponse
    {
        $review->update([
            'is_approved' => false,
            'moderation_status' => 'rejected',
            'moderated_at' => now(),
        ]);

        return back()->with('status', 'Review rejected successfully.');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'Review deleted successfully.');
    }
}
