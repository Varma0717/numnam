@extends('store.layouts.app')

@section('title', 'NumNam - My Account')

@section('content')
{{-- Header --}}
<section class="section pb-4 pt-8">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="inline-flex rounded-full border border-numnam-200 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-numnam-700">Welcome back</span>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900">{{ auth()->user()->name }}</h1>
        </div>
        <form method="POST" action="{{ route('store.logout') }}">
            @csrf
            <button class="rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-600 transition hover:border-slate-300 hover:bg-slate-50" type="submit">Log Out</button>
        </form>
    </div>
</section>

{{-- Stats --}}
<section class="section pb-4">
    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm">
            <p class="text-3xl font-extrabold text-slate-900">{{ $orders->count() }}</p>
            <p class="mt-1 text-sm text-slate-500">Orders</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm">
            <p class="text-3xl font-extrabold text-slate-900">{{ $subscriptions->where('status', 'active')->count() }}</p>
            <p class="mt-1 text-sm text-slate-500">Active Subscriptions</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm">
            <p class="text-3xl font-extrabold text-slate-900">{{ $referrals->count() }}</p>
            <p class="mt-1 text-sm text-slate-500">Referrals</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm">
            <p class="text-3xl font-extrabold text-numnam-600">Rs {{ number_format($rewardBalance, 0) }}</p>
            <p class="mt-1 text-sm text-slate-500">Reward Balance</p>
        </div>
    </div>
</section>

{{-- Tabs --}}
<section class="section pb-14 account-section">
    <div class="account-tabs flex gap-1 overflow-x-auto rounded-2xl border border-slate-200 bg-slate-100 p-1" role="tablist" aria-label="Account sections">
        <button class="account-tab active" data-tab="orders" role="tab" aria-selected="true" aria-controls="panel-orders">Orders</button>
        <button class="account-tab" data-tab="subscriptions" role="tab" aria-selected="false" aria-controls="panel-subscriptions">Subscriptions</button>
        <button class="account-tab" data-tab="referrals" role="tab" aria-selected="false" aria-controls="panel-referrals">Referrals</button>
        <button class="account-tab" data-tab="rewards" role="tab" aria-selected="false" aria-controls="panel-rewards">Rewards</button>
    </div>

    {{-- Orders Panel --}}
    <div class="account-panel active mt-4" data-panel="orders" id="panel-orders" role="tabpanel">
        @if($orders->isEmpty())
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-slate-500">You haven't placed any orders yet.</p>
            <a class="mt-4 inline-flex h-10 items-center rounded-full bg-numnam-600 px-6 text-sm font-semibold text-white hover:bg-numnam-700" href="{{ route('store.products') }}">Start Shopping</a>
        </div>
        @else
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Order #</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Total</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 text-sm font-semibold text-slate-900">{{ $order->order_number }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3"><span class="inline-flex rounded-full border border-numnam-200 bg-numnam-50 px-2.5 py-0.5 text-xs font-semibold text-numnam-700">{{ ucfirst($order->status) }}</span></td>
                            <td class="px-5 py-3 text-sm font-medium text-slate-900">Rs {{ number_format($order->total, 0) }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ strtoupper($order->payment_status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Subscriptions Panel --}}
    <div class="account-panel mt-4" data-panel="subscriptions" id="panel-subscriptions" role="tabpanel">
        @if($subscriptions->isEmpty())
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-slate-500">No active subscriptions.</p>
            <a class="mt-4 inline-flex h-10 items-center rounded-full bg-numnam-600 px-6 text-sm font-semibold text-white hover:bg-numnam-700" href="{{ route('store.pricing') }}">View Plans</a>
        </div>
        @else
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Plan</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Cycle</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Price</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($subscriptions as $subscription)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 text-sm font-semibold text-slate-900">{{ $subscription->plan_name }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ ucfirst($subscription->frequency) }}</td>
                            <td class="px-5 py-3 text-sm font-medium text-slate-900">Rs {{ number_format($subscription->price_per_cycle, 0) }}</td>
                            <td class="px-5 py-3"><span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">{{ ucfirst($subscription->status) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Referrals Panel --}}
    <div class="account-panel mt-4" data-panel="referrals" id="panel-referrals" role="tabpanel">
        <div class="mb-4 rounded-2xl border border-numnam-100 bg-numnam-50/60 p-5">
            <p class="text-sm font-semibold text-slate-700">Your Referral Code</p>
            <div class="mt-2 flex flex-wrap items-center gap-3">
                <strong class="rounded-xl border border-numnam-200 bg-white px-4 py-2 font-mono text-sm text-slate-900" id="account-referral-code">{{ auth()->user()->referral_code ?: 'Not generated yet' }}</strong>
                @if(auth()->user()->referral_code)
                <button class="rounded-xl border border-numnam-200 bg-white px-3 py-2 text-xs font-semibold text-numnam-700 transition hover:bg-numnam-50" type="button" onclick="navigator.clipboard.writeText(document.getElementById('account-referral-code').textContent.trim()).then(()=>{this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)})">Copy</button>
                @endif
            </div>
            @if(auth()->user()->referral_code)
            <p class="mt-2 text-xs text-slate-500">Share link: <span class="font-medium text-slate-700">{{ route('store.register', ['ref' => auth()->user()->referral_code]) }}</span></p>
            @endif
        </div>
        @if($referrals->isEmpty())
        <p class="text-sm text-slate-500">No referrals yet. Share your code with friends!</p>
        @else
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Customer</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($referrals as $referral)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 text-sm text-slate-900">{{ $referral->name }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $referral->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    {{-- Rewards Panel --}}
    <div class="account-panel mt-4" data-panel="rewards" id="panel-rewards" role="tabpanel">
        @if($rewards->isEmpty())
        <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
            <p class="text-slate-500">No reward transactions yet. Refer friends to earn rewards!</p>
            <a class="mt-4 inline-flex h-10 items-center rounded-full bg-numnam-600 px-6 text-sm font-semibold text-white hover:bg-numnam-700" href="{{ route('store.refer-friends') }}">Learn About Referrals</a>
        </div>
        @else
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Type</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rewards as $reward)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $reward->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3"><span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-semibold uppercase text-slate-600">{{ $reward->type }}</span></td>
                            <td class="px-5 py-3 text-sm font-medium text-slate-900">Rs {{ number_format($reward->amount, 0) }}</td>
                            <td class="px-5 py-3 text-sm text-slate-600">{{ $reward->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection