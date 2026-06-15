@extends('admin.layouts.app')

@section('title', 'Tools Usage Analytics')

@section('content')
<div class="space-y-8 bg-slate-50 p-8">
    <!-- Header Section -->
    <div class="border-b border-slate-200 pb-6">
        <h1 class="text-4xl font-extrabold text-slate-900">📊 Tools Analytics Dashboard</h1>
        <p class="mt-2 text-lg text-slate-600">Track real-time customer engagement with NumNam tools</p>
    </div>

    <!-- Date Filter Section -->
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-sm font-semibold uppercase text-slate-600">Filter by Date Range</h3>
        <form method="GET" action="{{ route('admin.tools.index') }}" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">From Date</label>
                <input type="date" name="from" value="{{  }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to" value="{{  }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <button type="submit" class="rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-2.5 font-semibold text-white transition hover:shadow-lg">
                🔍 Filter
            </button>
        </form>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Feed Logs Card -->
        <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50 to-blue-100 p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Feed Logs</p>
                    <p class="mt-2 text-3xl font-bold text-blue-900">{{ \['total_feed_logs'] }}</p>
                    <p class="mt-1 text-xs text-blue-700">Total logs recorded</p>
                </div>
                <div class="text-4xl">📝</div>
            </div>
        </div>

        <!-- Chat Messages Card -->
        <div class="rounded-xl border border-purple-100 bg-gradient-to-br from-purple-50 to-purple-100 p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Chat Messages</p>
                    <p class="mt-2 text-3xl font-bold text-purple-900">{{ \['total_chat_messages'] }}</p>
                    <p class="mt-1 text-xs text-purple-700">Community conversations</p>
                </div>
                <div class="text-4xl">💬</div>
            </div>
        </div>

        <!-- Active Logging Users Card -->
        <div class="rounded-xl border border-green-100 bg-gradient-to-br from-green-50 to-green-100 p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Logging Users</p>
                    <p class="mt-2 text-3xl font-bold text-green-900">{{ \['active_logging_users'] }}</p>
                    <p class="mt-1 text-xs text-green-700">Active in last 30 days</p>
                </div>
                <div class="text-4xl">👨‍👩‍👧</div>
            </div>
        </div>

        <!-- Active Chat Users Card -->
        <div class="rounded-xl border border-orange-100 bg-gradient-to-br from-orange-50 to-orange-100 p-6 shadow-sm transition hover:shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Community Users</p>
                    <p class="mt-2 text-3xl font-bold text-orange-900">{{ \['active_chat_users'] }}</p>
                    <p class="mt-1 text-xs text-orange-700">Chatting & engaging</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
    </div>

    <!-- Log Types Breakdown -->
    @if(\['feed_log_types'])
    <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-2xl font-bold text-slate-900">📊 Log Types Breakdown</h2>
            <p class="mt-1 text-sm text-slate-600">Distribution of different feed log types</p>
        </div>
        <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
            @forelse(\['feed_log_types'] as \ => \)
            <div class="rounded-lg border-2 border-slate-200 bg-slate-50 p-6 text-center transition hover:border-slate-300 hover:bg-white">
                <div class="mb-3 text-5xl">
                    @switch(\)
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
                <p class="mb-1 text-sm font-medium capitalize text-slate-600">{{ \ }} Logs</p>
                <p class="text-2xl font-bold text-slate-900">{{ \['count'] }}</p>
                <p class="mt-2 text-xs text-slate-500">recorded</p>
            </div>
            @empty
            <div class="col-span-4 text-center py-8">
                <p class="text-slate-500">No logs recorded yet</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- Top Users Section -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Top Logging Users -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">🥇 Top Logging Users</h2>
                    <p class="mt-1 text-sm text-slate-600">Most active feed loggers</p>
                </div>
                <a href="{{ route('admin.tools.feed-logs') }}" class="rounded-lg bg-blue-100 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-200">
                    View All →
                </a>
            </div>
            <div class="space-y-2">
                @forelse(\->take(5) as \)
                <div class="group rounded-lg border border-slate-200 bg-gradient-to-r from-slate-50 to-transparent p-4 transition hover:bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ \->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-slate-500">{{ \->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">{{ \->log_count }}</p>
                            <p class="text-xs text-slate-500">{{ \->days_used }} day{{ \->days_used != 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    @if(\->user)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.tools.customer-detail', \->user->id) }}" class="text-xs font-medium text-blue-600 transition group-hover:text-blue-700">
                            📋 View Detailed Report →
                        </a>
                    </div>
                    @endif
                </div>
                @empty
                <div class="py-8 text-center">
                    <p class="text-slate-500">📭 No logging activity yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Top Community Users -->
        <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">💬 Top Community Users</h2>
                    <p class="mt-1 text-sm text-slate-600">Most active chatters</p>
                </div>
                <a href="{{ route('admin.tools.community') }}" class="rounded-lg bg-purple-100 px-3 py-1.5 text-sm font-medium text-purple-700 transition hover:bg-purple-200">
                    View All →
                </a>
            </div>
            <div class="space-y-2">
                @forelse(\->take(5) as \)
                <div class="group rounded-lg border border-slate-200 bg-gradient-to-r from-slate-50 to-transparent p-4 transition hover:bg-purple-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ \->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-slate-500">{{ \->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">{{ \->message_count }}</p>
                            <p class="text-xs text-slate-500">in {{ \->rooms_count }} room{{ \->rooms_count != 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    @if(\->user)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.tools.customer-detail', \->user->id) }}" class="text-xs font-medium text-purple-600 transition group-hover:text-purple-700">
                            📋 View Detailed Report →
                        </a>
                    </div>
                    @endif
                </div>
                @empty
                <div class="py-8 text-center">
                    <p class="text-slate-500">📭 No chat activity yet</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
