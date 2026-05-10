<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponManagementController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()->latest('id')->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:32|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'min_subtotal' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:32|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0.01',
            'min_subtotal' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('status', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return back()->with('status', 'Coupon deleted successfully.');
    }

    public function bulk(Request $request): RedirectResponse
    {
        $request->validate([
            'bulk_action' => 'required|in:activate,deactivate,delete',
            'ids'         => 'required|array',
            'ids.*'       => 'integer|exists:coupons,id',
        ]);

        $ids = $request->input('ids');

        match ($request->input('bulk_action')) {
            'activate'   => Coupon::whereIn('id', $ids)->update(['is_active' => true]),
            'deactivate' => Coupon::whereIn('id', $ids)->update(['is_active' => false]),
            'delete'     => Coupon::whereIn('id', $ids)->delete(),
        };

        return redirect()->route('admin.coupons.index')->with('status', 'Bulk action applied to ' . count($ids) . ' coupons.');
    }
}
