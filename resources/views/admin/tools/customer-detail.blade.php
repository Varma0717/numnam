@extends('admin.layouts.app')

@section('title', $user->name . ' - Tools Activity')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">{{ $user->name }}'s Tools Activity</h1>
            <p class="mt-1 text-slate-600">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.customers.show', $user->id) }}" class="rounded bg-slate-600 px-4 py-2 text-white hover:bg-slate-700">View Full Profile</a>
    </div>

    <!-- Date Filter -->
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <form method="GET" action="{{ route('admin.tools.customer-detail', $user->id) }}" class="flex gap-4">
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

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Logs -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Total Feed Logs</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $summary['total_logs'] }}</p>
                </div>
                <div class="text-3xl">📝</div>
            </div>
            @if($summary['last_log_date'])
            <p class="mt-4 text-xs text-slate-500">Last log: {{ $summary['last_log_date']->format('M d, Y H:i') }}</p>
            @endif
        </div>

        <!-- Milk Logs -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Milk Logs</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $summary['log_types']['milk'] ?? 0 }}</p>
                </div>
                <div class="text-3xl">🍼</div>
            </div>
        </div>

        <!-- Solid Logs -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Solid Logs</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $summary['log_types']['solid'] ?? 0 }}</p>
                </div>
                <div class="text-3xl">🥣</div>
            </div>
        </div>

        <!-- Chat Activity -->
        <div class="rounded-lg border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-600">Chat Messages</p>
                    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $summary['total_chat_messages'] }}</p>
                </div>
                <div class="text-3xl">💬</div>
            </div>
            @if($summary['last_chat_date'])
            <p class="mt-4 text-xs text-slate-500">Last message: {{ $summary['last_chat_date']->format('M d, Y H:i') }}</p>
            @endif
        </div>
    </div>

    <!-- Baby Profiles -->
    @if($user->babyProfiles->isNotEmpty())
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-slate-900">Baby Profiles</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($user->babyProfiles as $baby)
            <div class="rounded border border-slate-100 p-4">
                <p class="font-semibold text-slate-900">{{ $baby->baby_name ?? 'Baby' }}</p>
                <div class="mt-2 space-y-1 text-sm text-slate-600">
                    <p>Age: {{ $baby->age_months }} months</p>
                    <p>Weight: {{ $baby->weight_kg }} kg</p>
                    <p>Milk Type: {{ $baby->milk_type ?? 'Not specified' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Feed Logs Table -->
        <div class="rounded-lg border border-slate-200 bg-white overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-3">
                <h2 class="font-semibold text-slate-900">📝 Feed Logs ({{ $feedLogs->count() }})</h2>
            </div>
            @if($feedLogs->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Details</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($feedLogs->take(10) as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-3">
                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700">
                                    @switch($log->type)
                                    @case('milk')
                                    🍼 Milk
                                    @break
                                    @case('solid')
                                    🥣 Solid
                                    @break
                                    @case('water')
                                    💧 Water
                                    @break
                                    @case('poop')
                                    💩 Poop
                                    @break
                                    @endswitch
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-600">
                                @switch($log->type)
                                @case('milk')
                                {{ $log->volume_ml }}ml {{ $log->milk_type ?? '' }}
                                @break
                                @case('solid')
                                {{ $log->food_name ?? 'Food' }}
                                @break
                                @case('water')
                                {{ $log->volume_ml }}ml
                                @break
                                @case('poop')
                                {{ $log->poop_type ?? 'Recorded' }}
                                @break
                                @endswitch
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-500">
                                {{ $log->logged_at->format('M d H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($feedLogs->count() > 10)
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-3 text-center text-sm text-slate-600">
                Showing 10 of {{ $feedLogs->count() }} logs
            </div>
            @endif
            @else
            <div class="px-6 py-8 text-center text-slate-500">No feed logs in selected period</div>
            @endif
        </div>

        <!-- Chat Messages Table -->
        <div class="rounded-lg border border-slate-200 bg-white overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-3">
                <h2 class="font-semibold text-slate-900">💬 Chat Messages ({{ $chatMessages->count() }})</h2>
            </div>
            @if($chatMessages->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Room</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Message</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($chatMessages->take(10) as $message)
                        <tr class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-3 text-sm font-medium text-slate-900">
                                {{ $message->room->name }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-600">
                                {{ Str::limit($message->message, 40) }}
                            </td>
                            <td class="px-6 py-3 text-sm text-slate-500">
                                {{ $message->created_at->format('M d H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($chatMessages->count() > 10)
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-3 text-center text-sm text-slate-600">
                Showing 10 of {{ $chatMessages->count() }} messages
            </div>
            @endif
            @else
            <div class="px-6 py-8 text-center text-slate-500">No chat messages in selected period</div>
            @endif
        </div>
    </div>

</div>
@endsection