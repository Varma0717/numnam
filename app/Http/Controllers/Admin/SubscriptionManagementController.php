<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewSubscriptionAdminNotification;
use App\Mail\SubscriptionStatusNotification;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriptionManagementController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::query()
            ->with('user')
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->q, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$request->q}%")))
            ->latest('id')
            ->paginate(25);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('user');

        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $previousStatus = $subscription->status;
        $data = $request->validate([
            'status' => 'required|in:active,paused,cancelled',
        ]);

        $subscription->update($data);

        if ($subscription->user?->email && $previousStatus !== $subscription->status) {
            try {
                $action = match ($subscription->status) {
                    'active' => 'resumed',
                    'paused' => 'paused',
                    'cancelled' => 'cancelled',
                    default => 'updated',
                };

                Mail::to($subscription->user->email)->queue(
                    new SubscriptionStatusNotification($subscription->fresh()->loadMissing('user'), $action)
                );

                $adminRecipient = (string) config('mail.order_recipient', '');
                if ($adminRecipient !== '') {
                    Mail::to($adminRecipient)->queue(
                        new NewSubscriptionAdminNotification($subscription->fresh()->loadMissing('user'), $action)
                    );
                }
            } catch (\Throwable $exception) {
                report($exception);
            }
        }

        return redirect()->route('admin.subscriptions.index')->with('status', 'Subscription updated.');
    }
}
