@extends('admin.layouts.app')

@section('title', 'Tools Usage Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">NumNam Tools Analytics</h1>
        <p class="mt-1 text-slate-600">Track customer usage of Logging, Community Chat, and other tools</p>
    </div>

    <!-- Date Filter -->
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <form method="GET" action="{{ route('admin.tools.index') }}" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">From Date</label>
                <input type="date" name="from" value="{{ $from }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to" value="{{ $to }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Feed Logs Card -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Feed Logs</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['total_feed_logs'] }}</p>
                </div>
                <div class="text-3xl">📝</div>
            </div>
            <p class="mt-4 text-xs text-slate-500">Milk, Solids, Water, Poop logs</p>
        </div>

        <!-- Chat Messages Card -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Chat Messages</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['total_chat_messages'] }}</p>
                </div>
                <div class="text-3xl">💬</div>
            </div>
            <p class="mt-4 text-xs text-slate-500">Community room messages</p>
        </div>

        <!-- Active Logging Users Card -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Users Logging</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['active_logging_users'] }}</p>
                </div>
                <div class="text-3xl">👨‍👩‍👧</div>
            </div>
            <p class="mt-4 text-xs text-slate-500">Active in logging tool</p>
        </div>

        <!-- Active Chat Users Card -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Users Chatting</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $stats['active_chat_users'] }}</p>
                </div>
                <div class="text-3xl">👥</div>
            </div>
            <p class="mt-4 text-xs text-slate-500">Active in community</p>
        </div>
    </div>

    <!-- Log Types Breakdown -->
    @if($stats['feed_log_types'])
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-slate-900">Feed Log Types Breakdown</h2>
        <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-4">
            @forelse($stats['feed_log_types'] as $type => $data)
            <div class="rounded border border-slate-100 bg-slate-50 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-2xl">
                        @switch($type)
                        @case('milk')
                        🍼
                        @break
                        @case('solid')
                        🥣
                        @break
                        @case('water')
                        💧
                        @break
                        @case('poop')
                        💩
                        @break
                        @default
                        📊
                        @endswitch
                    </div>
                    <div>
                        <p class="capitalize text-xs font-medium text-slate-600">{{ $type }}</p>
                        <p class="text-lg font-bold text-slate-900">{{ $data['count'] }}</p>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-slate-500">No logs recorded</p>
            @endforelse
        </div>
    </div>
    @endif

    <!-- Top Logging Users -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Top Logging Users</h2>
                <a href="{{ route('admin.tools.feed-logs') }}" class="text-sm text-blue-600 hover:text-blue-700">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse($feedLogUsers->take(5) as $user)
                <div class="flex items-center justify-between rounded border border-slate-100 p-3">
                    <div>
                        <p class="font-medium text-slate-900">{{ $user->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-500">{{ $user->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-900">{{ $user->log_count }}</p>
                        <p class="text-xs text-slate-500">{{ $user->days_used }} days</p>
                    </div>
                </div>
                <a href="{{ route('admin.tools.customer-detail', $user->user->id) }}" class="text-xs text-blue-600 hover:underline">View details →</a>
                @empty
                <p class="text-slate-500">No logging activity yet</p>
                @endforelse
            </div>
        </div>

        <!-- Top Chat Users -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Top Community Users</h2>
                <a href="{{ route('admin.tools.community') }}" class="text-sm text-blue-600 hover:text-blue-700">View all →</a>
            </div>
            <div class="space-y-3">
                @forelse($chatUsers->take(5) as $user)
                <div class="flex items-center justify-between rounded border border-slate-100 p-3">
                    <div>
                        <p class="font-medium text-slate-900">{{ $user->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-slate-500">{{ $user->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-900">{{ $user->message_count }}</p>
                        <p class="text-xs text-slate-500">{{ $user->rooms_count }} rooms</p>
                    </div>
                </div>
                <a href="{{ route('admin.tools.customer-detail', $user->user->id) }}" class="text-xs text-blue-600 hover:underline">View details →</a>
                @empty
                <p class="text-slate-500">No chat activity yet</p>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection