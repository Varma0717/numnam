<?php

namespace App\Http\Controllers\Admin\Commerce;

use App\Http\Controllers\Controller;
use App\Models\RewardLedger;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralManagementController extends Controller
{
    public function index(Request $request)
    {
        $referrers = User::query()
            ->whereNotNull('referral_code')
            ->withCount('referrals')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $rewardEntries = RewardLedger::query()
            ->with(['user', 'order'])
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.referrals.index', compact('referrers', 'rewardEntries'));
    }

    public function storeAdjustment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        RewardLedger::create([
            'user_id' => (int) $data['user_id'],
            'type' => $data['type'],
            'amount' => (float) $data['amount'],
            'description' => $data['description'],
            'meta' => ['source' => 'admin_adjustment'],
        ]);

        return back()->with('status', 'Referral reward adjustment created.');
    }

    public function destroyReward(RewardLedger $reward): RedirectResponse
    {
        $reward->delete();

        return back()->with('status', 'Reward entry deleted.');
    }
}
