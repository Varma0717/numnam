<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\CommunityRoom;
use App\Models\MessageComment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NumNamCommunityController extends Controller
{
    /**
     * Get all community rooms
     */
    public function rooms()
    {
        $rooms = CommunityRoom::where('is_active', true)
            ->with(['messages' => function ($q) {
                $q->select('id', 'room_id', 'user_id', 'created_at')
                    ->latest('created_at')
                    ->limit(1);
            }])
            ->withCount('messages')
            ->orderBy('display_order')
            ->get()
            ->map(function ($room) {
                $lastMessage = $room->messages->first();
                return [
                    'id' => $room->id,
                    'name' => $room->name,
                    'description' => $room->description,
                    'icon' => $room->icon,
                    'color' => $room->color,
                    'slug' => $room->slug,
                    'message_count' => $room->messages_count,
                    'last_activity' => $lastMessage?->created_at?->diffForHumans(),
                ];
            });

        return response()->json(['data' => $rooms]);
    }

    /**
     * Get messages for a specific room with pagination
     */
    public function roomMessages(Request $request, CommunityRoom $room)
    {
        $page = $request->get('page', 1);
        $perPage = 15; // Reduced for better performance

        $messages = ChatMessage::where('room_id', $room->id)
            ->with(['user:id,name,avatar', 'comments' => function ($q) {
                $q->with('user:id,name,avatar')->latest('created_at')->limit(3);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['id', 'room_id', 'user_id', 'message', 'likes_count', 'comments_count', 'created_at', 'updated_at'], 'page', $page);

        return response()->json([
            'data' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'last_page' => $messages->lastPage(),
            ]
        ]);
    }

    /**
     * Send a message to a room (authenticated only)
     */
    public function sendMessage(Request $request, CommunityRoom $room)
    {
        $user = $request->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['You must be logged in to send messages.']
            ]);
        }

        $validated = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        // Prevent spam: check if user posted in last 2 seconds
        $lastMessage = ChatMessage::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->where('created_at', '>=', now()->subSeconds(2))
            ->first();

        if ($lastMessage) {
            throw ValidationException::withMessages([
                'message' => ['Please wait a moment before posting again.']
            ]);
        }

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'likes_count' => 0,
            'comments_count' => 0,
        ]);

        $message->load('user:id,name,avatar');

        // Clear room cache
        \Illuminate\Support\Facades\Cache::forget("room_last_message_{$room->id}");

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message,
        ], 201);
    }

    /**
     * Like or unlike a message (with duplicate prevention)
     */
    public function toggleLike(Request $request, ChatMessage $message)
    {
        $user = $request->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['You must be logged in to like messages.']
            ]);
        }

        // Check if already liked
        $isLiked = $message->likedByUsers()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            // Unlike
            $message->likedByUsers()->detach($user->id);
            $message->decrementLikes();
        } else {
            // Like
            $message->likedByUsers()->attach($user->id);
            $message->incrementLikes();
        }

        return response()->json([
            'liked' => !$isLiked,
            'likes_count' => $message->fresh()->likes_count,
        ]);
    }

    /**
     * Add a comment to a message
     */
    public function addComment(Request $request, ChatMessage $message)
    {
        $user = $request->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['You must be logged in to comment.']
            ]);
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:1|max:500',
        ]);

        $comment = MessageComment::create([
            'message_id' => $message->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
            'likes_count' => 0,
        ]);

        // Increment comment count
        $message->increment('comments_count');

        $comment->load('user:id,name,avatar');

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment,
        ], 201);
    }

    /**
     * Get comments for a message
     */
    public function getComments(Request $request, ChatMessage $message)
    {
        $page = $request->get('page', 1);
        $perPage = 10;

        $comments = MessageComment::where('message_id', $message->id)
            ->with('user:id,name,avatar')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage, ['id', 'message_id', 'user_id', 'comment', 'likes_count', 'created_at'], 'page', $page);

        return response()->json([
            'data' => $comments->items(),
            'pagination' => [
                'current_page' => $comments->currentPage(),
                'total' => $comments->total(),
                'per_page' => $comments->perPage(),
            ]
        ]);
    }

    /**
     * Like a comment
     */
    public function likeComment(Request $request, MessageComment $comment)
    {
        $user = $request->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => ['You must be logged in.']
            ]);
        }

        $comment->incrementLikes();

        return response()->json([
            'likes_count' => $comment->fresh()->likes_count,
        ]);
    }

    /**
     * Search messages in a room
     */
    public function searchMessages(Request $request, CommunityRoom $room)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['error' => 'Query too short'], 400);
        }

        $messages = ChatMessage::where('room_id', $room->id)
            ->where('message', 'like', "%{$query}%")
            ->with('user:id,name,avatar')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'room' => ['id' => $room->id, 'name' => $room->name],
            'query' => $query,
            'results' => $messages,
            'count' => $messages->count(),
        ]);
    }
}
