@extends('admin.layouts.app')

@section('title', 'Tools Usage Analytics')

@push('styles')
<style>
    /* Tailwind CSS utilities for admin tools dashboard */
    .space-y-8>*+* {
        margin-top: 32px;
    }

    .space-y-2>*+* {
        margin-top: 8px;
    }

    .bg-slate-50 {
        background-color: #f8fafc;
    }

    .bg-white {
        background-color: #ffffff;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .bg-purple-50 {
        background-color: #faf5ff;
    }

    .bg-green-50 {
        background-color: #f0fdf4;
    }

    .bg-orange-50 {
        background-color: #fff7ed;
    }

    .bg-slate-100 {
        background-color: #f1f5f9;
    }

    .bg-gradient-to-br {
        background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }

    .bg-gradient-to-r {
        background-image: linear-gradient(to right, var(--tw-gradient-stops));
    }

    .from-blue-50 {
        --tw-gradient-stops: #eff6ff;
    }

    .from-blue-600 {
        --tw-gradient-stops: #2563eb;
    }

    .from-purple-50 {
        --tw-gradient-stops: #faf5ff;
    }

    .from-green-50 {
        --tw-gradient-stops: #f0fdf4;
    }

    .from-orange-50 {
        --tw-gradient-stops: #fff7ed;
    }

    .from-slate-50 {
        --tw-gradient-stops: #f8fafc;
    }

    .to-blue-100 {
        --tw-gradient-stops: var(--tw-gradient-stops), #dbeafe;
    }

    .to-blue-700 {
        --tw-gradient-stops: var(--tw-gradient-stops), #1d4ed8;
    }

    .to-purple-100 {
        --tw-gradient-stops: var(--tw-gradient-stops), #f3e8ff;
    }

    .to-green-100 {
        --tw-gradient-stops: var(--tw-gradient-stops), #dcfce7;
    }

    .to-orange-100 {
        --tw-gradient-stops: var(--tw-gradient-stops), #ffedd5;
    }

    .to-transparent {
        --tw-gradient-stops: var(--tw-gradient-stops), transparent;
    }

    .p-8 {
        padding: 32px;
    }

    .p-6 {
        padding: 24px;
    }

    .p-4 {
        padding: 16px;
    }

    .px-4 {
        padding-left: 16px;
        padding-right: 16px;
    }

    .px-3 {
        padding-left: 12px;
        padding-right: 12px;
    }

    .px-6 {
        padding-left: 24px;
        padding-right: 24px;
    }

    .py-2\.5 {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .py-1\.5 {
        padding-top: 6px;
        padding-bottom: 6px;
    }

    .py-8 {
        padding-top: 32px;
        padding-bottom: 32px;
    }

    .mt-2 {
        margin-top: 8px;
    }

    .mt-1 {
        margin-top: 4px;
    }

    .mt-3 {
        margin-top: 12px;
    }

    .mt-6 {
        margin-top: 24px;
    }

    .mb-4 {
        margin-bottom: 16px;
    }

    .mb-6 {
        margin-bottom: 24px;
    }

    .mb-3 {
        margin-bottom: 12px;
    }

    .mb-1 {
        margin-bottom: 4px;
    }

    .pt-3 {
        padding-top: 12px;
    }

    .pb-6 {
        padding-bottom: 24px;
    }

    .pb-4 {
        padding-bottom: 16px;
    }

    .rounded-xl {
        border-radius: 16px;
    }

    .rounded-lg {
        border-radius: 8px;
    }

    .border {
        border: 1px solid;
    }

    .border-2 {
        border-width: 2px;
    }

    .border-b {
        border-bottom: 1px solid;
    }

    .border-t {
        border-top: 1px solid;
    }

    .border-slate-50 {
        border-color: #f8fafc;
    }

    .border-slate-200 {
        border-color: #e2e8f0;
    }

    .border-slate-300 {
        border-color: #cbd5e1;
    }

    .border-blue-100 {
        border-color: #dbeafe;
    }

    .border-purple-100 {
        border-color: #f3e8ff;
    }

    .border-green-100 {
        border-color: #dcfce7;
    }

    .border-orange-100 {
        border-color: #ffedd5;
    }

    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .text-sm {
        font-size: 14px;
    }

    .text-xs {
        font-size: 12px;
    }

    .text-lg {
        font-size: 18px;
    }

    .text-2xl {
        font-size: 24px;
    }

    .text-3xl {
        font-size: 30px;
    }

    .text-4xl {
        font-size: 36px;
    }

    .text-5xl {
        font-size: 48px;
    }

    .font-medium {
        font-weight: 500;
    }

    .font-semibold {
        font-weight: 600;
    }

    .font-bold {
        font-weight: 700;
    }

    .font-extrabold {
        font-weight: 800;
    }

    .text-slate-500 {
        color: #64748b;
    }

    .text-slate-600 {
        color: #475569;
    }

    .text-slate-700 {
        color: #334155;
    }

    .text-slate-900 {
        color: #0f172a;
    }

    .text-blue-600 {
        color: #2563eb;
    }

    .text-blue-700 {
        color: #1d4ed8;
    }

    .text-blue-900 {
        color: #1e3a8a;
    }

    .text-purple-600 {
        color: #9333ea;
    }

    .text-purple-700 {
        color: #7e22ce;
    }

    .text-purple-900 {
        color: #581c87;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .text-green-700 {
        color: #15803d;
    }

    .text-green-900 {
        color: #166534;
    }

    .text-orange-600 {
        color: #ea580c;
    }

    .text-orange-700 {
        color: #c2410c;
    }

    .text-orange-900 {
        color: #92400e;
    }

    .text-white {
        color: #ffffff;
    }

    .flex {
        display: flex;
    }

    .grid {
        display: grid;
    }

    .block {
        display: block;
    }

    .w-full {
        width: 100%;
    }

    .flex-1 {
        flex: 1;
    }

    .flex-col {
        flex-direction: column;
    }

    .items-start {
        align-items: flex-start;
    }

    .items-center {
        align-items: center;
    }

    .items-end {
        align-items: flex-end;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-4 {
        gap: 16px;
    }

    .gap-6 {
        gap: 24px;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .col-span-4 {
        grid-column: span 4 / span 4;
    }

    .transition {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    .uppercase {
        text-transform: uppercase;
    }

    .capitalize {
        text-transform: capitalize;
    }

    .hover\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .hover\:shadow-lg:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .hover\:bg-white:hover {
        background-color: #ffffff;
    }

    .hover\:bg-blue-50:hover {
        background-color: #eff6ff;
    }

    .hover\:bg-blue-200:hover {
        background-color: #bfdbfe;
    }

    .hover\:bg-purple-50:hover {
        background-color: #faf5ff;
    }

    .hover\:bg-purple-200:hover {
        background-color: #e9d5ff;
    }

    .hover\:border-slate-300:hover {
        border-color: #cbd5e1;
    }

    .hover\:text-blue-700:hover {
        color: #1d4ed8;
    }

    .hover\:text-purple-700:hover {
        color: #7e22ce;
    }

    .focus\:border-blue-500:focus {
        border-color: #3b82f6;
    }

    .focus\:ring-1:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow);
    }

    .focus\:ring-blue-500:focus {
        --tw-ring-color: #3b82f6;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    @media (min-width: 768px) {
        .md\:flex-row {
            flex-direction: row;
        }

        .md\:items-end {
            align-items: flex-end;
        }

        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .md\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (min-width: 1024px) {
        .lg\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .lg\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endpush

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
                <input type="date" name="from" value="{{ $from }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to" value="{{ $to }}" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-slate-900 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
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
                    <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['total_feed_logs'] }}</p>
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
                    <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['total_chat_messages'] }}</p>
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
                    <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['active_logging_users'] }}</p>
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
                    <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['active_chat_users'] }}</p>
                    <p class="mt-1 text-xs text-orange-700">Chatting & engaging</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>
    </div>

    <!-- Log Types Breakdown -->
    @if($stats['feed_log_types'])
    <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6 border-b border-slate-200 pb-4">
            <h2 class="text-2xl font-bold text-slate-900">📊 Log Types Breakdown</h2>
            <p class="mt-1 text-sm text-slate-600">Distribution of different feed log types</p>
        </div>
        <div class="grid grid-cols-2 gap-6 md:grid-cols-4">
            @forelse($stats['feed_log_types'] as $type => $data)
            <div class="rounded-lg border-2 border-slate-200 bg-slate-50 p-6 text-center transition hover:border-slate-300 hover:bg-white">
                <div class="mb-3 text-5xl">
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
                <p class="mb-1 text-sm font-medium capitalize text-slate-600">{{ $type }} Logs</p>
                <p class="text-2xl font-bold text-slate-900">{{ $data['count'] }}</p>
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
                @forelse($feedLogUsers->take(5) as $user)
                <div class="group rounded-lg border border-slate-200 bg-gradient-to-r from-slate-50 to-transparent p-4 transition hover:bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ $user->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-slate-500">{{ $user->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">{{ $user->log_count }}</p>
                            <p class="text-xs text-slate-500">{{ $user->days_used }} day{{ $user->days_used != 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    @if($user->user)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.tools.customer-detail', $user->user->id) }}" class="text-xs font-medium text-blue-600 transition group-hover:text-blue-700">
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
                @forelse($chatUsers->take(5) as $user)
                <div class="group rounded-lg border border-slate-200 bg-gradient-to-r from-slate-50 to-transparent p-4 transition hover:bg-purple-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900">{{ $user->user->name ?? 'Unknown User' }}</p>
                            <p class="text-sm text-slate-500">{{ $user->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">{{ $user->message_count }}</p>
                            <p class="text-xs text-slate-500">in {{ $user->rooms_count }} room{{ $user->rooms_count != 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    @if($user->user)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.tools.customer-detail', $user->user->id) }}" class="text-xs font-medium text-purple-600 transition group-hover:text-purple-700">
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