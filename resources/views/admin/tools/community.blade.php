@extends('admin.layouts.app')

@section('title', 'Community Chat Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-900">💬 Community Chat Activity</h1>
        <p class="mt-1 text-slate-600">Track conversations and participation in community rooms</p>
    </div>

    <!-- Filters -->
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <form method="GET" action="{{ route('admin.tools.community') }}" class="grid gap-4 md:grid-cols-5">
            <div>
                <label class="block text-sm font-medium text-slate-700">From Date</label>
                <input type="date" name="from" value="{{ $from }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to" value="{{ $to }}" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Room</label>
                <select name="room_id" class="mt-1 w-full rounded border border-slate-300 px-3 py-2">
                    <option value="">All Rooms</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @selected($roomId==$room->id)>{{ $room->name }}</option>
                    @endforeach
                </select>
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
            <div class="flex items-end">
                <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Room Activity -->
    @if($roomActivity->isNotEmpty())
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Room Activity</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($roomActivity as $activity)
            <div class="rounded border border-slate-100 p-4 hover:border-slate-200">
                <p class="font-semibold text-slate-900">{{ $activity->room->name }}</p>
                <div class="mt-2 flex gap-4 text-sm">
                    <div>
                        <p class="text-slate-500">Messages</p>
                        <p class="text-lg font-bold text-slate-900">{{ $activity->message_count }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Users</p>
                        <p class="text-lg font-bold text-slate-900">{{ $activity->user_count }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- User Activity -->
    @if($userActivity->isNotEmpty())
    <div class="rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Top Community Members</h2>
        <div class="space-y-3">
            @foreach($userActivity->take(10) as $activity)
            <div class="flex items-center justify-between rounded border border-slate-100 p-4 hover:bg-slate-50">
                <div>
                    <p class="font-medium text-slate-900">{{ $activity->user->name ?? 'Unknown' }}</p>
                    <p class="text-xs text-slate-500">{{ $activity->user->email ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-slate-900">{{ $activity->message_count }} messages</p>
                    <p class="text-xs text-slate-500">{{ $activity->rooms_count }} rooms</p>
                </div>
                <a href="{{ route('admin.tools.customer-detail', $activity->user->id) }}" class="ml-4 text-blue-600 hover:text-blue-700">View →</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Messages List -->
    <div class="rounded-lg border border-slate-200 bg-white overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-3">
            <h2 class="font-semibold text-slate-900">Recent Messages</h2>
        </div>
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Room</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Message</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-slate-700">Sent At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($messages as $message)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3">
                        <div>
                            <p class="font-medium text-slate-900">{{ $message->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-500">{{ $message->user->email ?? 'N/A' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">{{ $message->room->name }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <p class="text-sm text-slate-600">{{ Str::limit($message->message, 50) }}</p>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-500">
                        {{ $message->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">No messages found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($messages->hasPages())
    <div class="flex justify-center">
        {{ $messages->links() }}
    </div>
    @endif

</div>
@endsection