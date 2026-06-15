<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedLog;
use App\Models\ChatMessage;
use App\Models\NumNamRecipe;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToolsManagementController extends Controller
{
    /**
     * Show Tools usage overview
     */
    public function index(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        // Get log counts per user
        $logCounts = FeedLog::whereBetween('feed_logs.created_at', [$from, $to . ' 23:59:59'])
            ->join('baby_profiles', 'feed_logs.baby_profile_id', '=', 'baby_profiles.id')
            ->select('baby_profiles.user_id', DB::raw('COUNT(*) as log_count'), DB::raw('COUNT(DISTINCT DATE(feed_logs.created_at)) as days_used'))
            ->groupBy('baby_profiles.user_id')
            ->get()
            ->keyBy('user_id');

        // Load users with their log counts
        $feedLogUsers = \App\Models\User::whereIn('id', $logCounts->keys())
            ->select('id', 'name', 'email')
            ->get()
            ->map(function ($user) use ($logCounts) {
                $counts = $logCounts[$user->id];
                return (object)[
                    'user' => $user,
                    'log_count' => $counts->log_count,
                    'days_used' => $counts->days_used,
                ];
            })
            ->sortByDesc('log_count');

        // Chat messages usage
        $chatUsers = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('user:id,name,email')
            ->select(DB::raw('user_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT room_id) as rooms_count'))
            ->groupBy('user_id')
            ->orderByDesc('message_count')
            ->get();

        // Overall statistics
        $stats = [
            'total_feed_logs' => FeedLog::whereBetween('feed_logs.created_at', [$from, $to . ' 23:59:59'])->count(),
            'total_chat_messages' => ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])->count(),
            'active_logging_users' => $feedLogUsers->count(),
            'active_chat_users' => $chatUsers->count(),
            'feed_log_types' => FeedLog::whereBetween('feed_logs.created_at', [$from, $to . ' 23:59:59'])
                ->select(DB::raw('type'), DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get()
                ->keyBy('type')
                ->toArray(),
        ];

        return view('admin.tools.index', compact(
            'from',
            'to',
            'feedLogUsers',
            'chatUsers',
            'stats'
        ));
    }

    /**
     * Show Feed Logs (Logging Tool) usage
     */
    public function feedLogs(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        $userId = $request->get('user_id');
        $type = $request->get('type'); // milk, solid, water, poop

        $query = FeedLog::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('babyProfile.user:id,name,email');

        if ($userId) {
            $query->whereHas('babyProfile', fn($q) => $q->where('user_id', $userId));
        }

        if ($type) {
            $query->where('type', $type);
        }

        $logs = $query->orderByDesc('logged_at')
            ->paginate(50);

        // Get daily breakdown for chart
        $dailyStats = FeedLog::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(
                DB::raw('DATE(logged_at) as date'),
                DB::raw('type'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $users = User::whereIn('id', FeedLog::distinct()
            ->join('baby_profiles', 'feed_logs.baby_profile_id', '=', 'baby_profiles.id')
            ->select('baby_profiles.user_id')
            ->pluck('user_id'))
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.tools.feed-logs', compact(
            'from',
            'to',
            'logs',
            'dailyStats',
            'users',
            'userId',
            'type'
        ));
    }

    /**
     * Show Community Chat usage
     */
    public function community(Request $request)
    {
        $from = $request->get('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));
        $roomId = $request->get('room_id');
        $userId = $request->get('user_id');

        $query = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('user:id,name,email', 'room:id,name');

        if ($roomId) {
            $query->where('room_id', $roomId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $messages = $query->orderByDesc('created_at')
            ->paginate(50);

        // Room activity
        $roomActivityData = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(DB::raw('room_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT user_id) as user_count'))
            ->groupBy('room_id')
            ->orderByDesc('message_count')
            ->get();

        $roomIds = $roomActivityData->pluck('room_id')->toArray();
        $rooms_map = \App\Models\CommunityRoom::whereIn('id', $roomIds)->get()->keyBy('id');

        $roomActivity = $roomActivityData->map(function ($item) use ($rooms_map) {
            return (object)[
                'room_id' => $item->room_id,
                'message_count' => $item->message_count,
                'user_count' => $item->user_count,
                'room' => $rooms_map[$item->room_id] ?? null,
            ];
        });

        // User activity
        $userActivityData = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(DB::raw('user_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT room_id) as rooms_count'))
            ->groupBy('user_id')
            ->orderByDesc('message_count')
            ->get();

        $userIds = $userActivityData->pluck('user_id')->toArray();
        $users_map = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get()->keyBy('id');

        $userActivity = $userActivityData->map(function ($item) use ($users_map) {
            return (object)[
                'user_id' => $item->user_id,
                'message_count' => $item->message_count,
                'rooms_count' => $item->rooms_count,
                'user' => $users_map[$item->user_id] ?? null,
            ];
        });

        $rooms = \App\Models\CommunityRoom::select('id', 'name')->get();
        $users = User::whereIn('id', ChatMessage::whereNotNull('user_id')
            ->distinct()
            ->select('user_id')
            ->pluck('user_id'))
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return view('admin.tools.community', compact(
            'from',
            'to',
            'messages',
            'roomActivity',
            'userActivity',
            'rooms',
            'users',
            'roomId',
            'userId'
        ));
    }

    /**
     * Show customer tools usage detail
     */
    public function customerDetail(Request $request, $userId)
    {
        $user = \App\Models\User::with('babyProfiles')->findOrFail($userId);

        $from = $request->get('from', now()->subDays(90)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        // Feed logs
        $feedLogs = FeedLog::whereHas('babyProfile', fn($q) => $q->where('user_id', $userId))
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('babyProfile:id,baby_name,user_id')
            ->orderByDesc('logged_at')
            ->get();

        // Chat messages
        $chatMessages = ChatMessage::where('user_id', $userId)
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('room:id,name')
            ->orderByDesc('created_at')
            ->get();

        // Summary
        $summary = [
            'total_logs' => $feedLogs->count(),
            'log_types' => $feedLogs->groupBy('type')->map->count(),
            'total_chat_messages' => $chatMessages->count(),
            'rooms_participated' => $chatMessages->groupBy('room_id')->count(),
            'last_log_date' => $feedLogs->max('logged_at'),
            'last_chat_date' => $chatMessages->max('created_at'),
        ];

        return view('admin.tools.customer-detail', compact(
            'user',
            'from',
            'to',
            'feedLogs',
            'chatMessages',
            'summary'
        ));
    }
}
