@extends('admin.layouts.app')

@section('title', 'Feed Logs Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">📝 Feed Logging Activity</h1>
        <p class="mt-1 text-slate-600">Track milk, solids, water, and poop logs from all users</p>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <form method="GET" action="{{ route('admin.tools.feed-logs') }}" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="block text-sm font-medium text-slate-700">From Date</label>
                <input type="date" name="from" value="{{ $from }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to" value="{{ $to }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">User</label>
                <select name="user_id" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected($userId==$user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Log Type</label>
                <select name="type" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    <option value="">All Types</option>
                    <option value="milk" @selected($type=='milk' )>🍼 Milk</option>
                    <option value="solid" @selected($type=='solid' )>🥣 Solids</option>
                    <option value="water" @selected($type=='water' )>💧 Water</option>
                    <option value="poop" @selected($type=='poop' )>💩 Poop</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="rounded-lg border border-slate-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Details</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Logged At</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm font-medium text-slate-700">
                            @switch($log->type)
                            @case('milk')
                            🍼 Milk
                            @break
                            @case('solid')
                            🥣 Solids
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
                    <td class="px-6 py-3">
                        <div>
                            <p class="font-medium text-slate-900">{{ $log->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-500">{{ $log->user->email ?? 'N/A' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-600">
                        @switch($log->type)
                        @case('milk')
                        <span>{{ $log->volume_ml }}ml {{ $log->milk_type ?? 'milk' }}</span>
                        @break
                        @case('solid')
                        <span>{{ $log->food_name ?? 'Food' }} ({{ $log->food_type ?? 'mixed' }})</span>
                        @break
                        @case('water')
                        <span>{{ $log->volume_ml }}ml water</span>
                        @break
                        @case('poop')
                        <span>{{ $log->poop_type ?? 'Type recorded' }}</span>
                        @break
                        @endswitch
                        @if($log->notes)
                        <div class="mt-1 text-xs text-slate-500">{{ $log->notes }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-500">
                        {{ $log->logged_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-3 text-sm">
                        <a href="{{ route('admin.tools.customer-detail', $log->user->id) }}" class="text-blue-600 hover:text-blue-700">View User</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No logs found for the selected filters</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($logs->hasPages())
    <div class="flex justify-center">
        {{ $logs->links() }}
    </div>
    @endif

</div>
@endsection