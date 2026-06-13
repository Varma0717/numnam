<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedLog;
use App\Models\ChatMessage;
use App\Models\NumNamRecipe;
use App\Models\Blog;
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

        // Feed logs usage
        $feedLogUsers = FeedLog::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('user:id,name,email')
            ->select(DB::raw('user_id'), DB::raw('COUNT(*) as log_count'), DB::raw('COUNT(DISTINCT DATE(created_at)) as days_used'))
            ->groupBy('user_id')
            ->orderByDesc('log_count')
            ->get();

        // Chat messages usage
        $chatUsers = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->with('user:id,name,email')
            ->select(DB::raw('user_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT room_id) as rooms_count'))
            ->groupBy('user_id')
            ->orderByDesc('message_count')
            ->get();

        // Overall statistics
        $stats = [
            'total_feed_logs' => FeedLog::whereBetween('created_at', [$from, $to . ' 23:59:59'])->count(),
            'total_chat_messages' => ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])->count(),
            'active_logging_users' => $feedLogUsers->count(),
            'active_chat_users' => $chatUsers->count(),
            'feed_log_types' => FeedLog::whereBetween('created_at', [$from, $to . ' 23:59:59'])
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
            ->with('user:id,name,email');

        if ($userId) {
            $query->where('user_id', $userId);
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

        $users = FeedLog::whereNotNull('user_id')
            ->distinct()
            ->select('user_id')
            ->with('user:id,name,email')
            ->get()
            ->map(fn($log) => $log->user)
            ->filter();

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
        $roomActivity = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(DB::raw('room_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT user_id) as user_count'))
            ->with('room:id,name')
            ->groupBy('room_id')
            ->orderByDesc('message_count')
            ->get();

        // User activity
        $userActivity = ChatMessage::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->select(DB::raw('user_id'), DB::raw('COUNT(*) as message_count'), DB::raw('COUNT(DISTINCT room_id) as rooms_count'))
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderByDesc('message_count')
            ->get();

        $rooms = \App\Models\CommunityRoom::select('id', 'name')->get();
        $users = ChatMessage::whereNotNull('user_id')
            ->distinct()
            ->select('user_id')
            ->with('user:id,name,email')
            ->get()
            ->map(fn($msg) => $msg->user)
            ->filter();

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
        $feedLogs = FeedLog::where('user_id', $userId)
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
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
