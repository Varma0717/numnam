<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ChatMessage;
use App\Models\CommunityRoom;
use Illuminate\Http\Request;

class NumNamCommunityController extends Controller
{
    /**
     * Get all community rooms
     */
    public function rooms()
    {
        $rooms = CommunityRoom::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return response()->json([
            'rooms' => $rooms,
        ]);
    }

    /**
     * Get messages for a specific room
     */
    public function roomMessages(Request $request, CommunityRoom $room)
    {
        $page = $request->get('page', 1);
        $perPage = 20;

        $messages = ChatMessage::where('room_id', $room->id)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'room' => $room,
            'messages' => $messages->reverse()->values(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
            ],
        ]);
    }

    /**
     * Send a message to a room
     */
    public function sendMessage(Request $request, CommunityRoom $room)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = ChatMessage::create([
            'room_id' => $room->id,
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
            'likes_count' => 0,
        ]);

        $message->load('user:id,name');

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message,
        ], 201);
    }

    /**
     * Like a message
     */
    public function likeMessage(Request $request, ChatMessage $message)
    {
        $message->incrementLikes();

        return response()->json([
            'message' => 'Message liked',
            'likes_count' => $message->likes_count,
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
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'room' => $room,
            'query' => $query,
            'results' => $messages,
            'count' => $messages->count(),
        ]);
    }
}
