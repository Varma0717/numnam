<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionManagementController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::query()
            ->with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->q, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$request->q}%")))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('user');

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'status' => 'required|in:active,paused,cancelled',
        ]);

        $subscription->update($data);

        return redirect()->route('admin.subscriptions.index')->with('status', 'Subscription updated.');
    }
}
