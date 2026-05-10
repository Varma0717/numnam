<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $messages = ContactMessage::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function show(ContactMessage $message): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $message]);
    }

    public function updateStatus(Request $request, ContactMessage $message): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
        ]);

        $payload = ['status' => $validated['status']];

        if ($validated['status'] === 'read' && !$message->read_at) {
            $payload['read_at'] = now();
        }

        if ($validated['status'] === 'replied' && !$message->replied_at) {
            $payload['replied_at'] = now();
        }

        $message->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Message status updated successfully.',
            'data' => $message->fresh(),
        ]);
    }

    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.',
        ]);
    }
}
