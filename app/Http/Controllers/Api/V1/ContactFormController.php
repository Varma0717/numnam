<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\ContactLeadNotification;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ContactFormController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        if (!Schema::hasTable('contact_messages')) {
            return response()->json([
                'success' => false,
                'message' => 'Contact form table is not migrated yet.',
            ], 400);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
            'source' => 'nullable|string|max:100',
        ]);

        $lead = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'source' => $validated['source'] ?? 'website',
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $recipient = config('mail.contact_recipient', config('mail.from.address'));

        try {
            if (!empty($recipient)) {
                Mail::to($recipient)->send(new ContactLeadNotification($lead));
                $lead->update(['notified_at' => now()]);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you. Your message has been submitted successfully.',
            'data' => [
                'id' => $lead->id,
                'status' => $lead->status,
            ],
        ], 201);
    }

    public function adminIndex(Request $request): JsonResponse
    {
        if (!Schema::hasTable('contact_messages')) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $messages = ContactMessage::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('source'), fn ($query) => $query->where('source', $request->string('source')))
            ->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    public function adminShow(ContactMessage $message): JsonResponse
    {
        if ($message->status === 'new') {
            $message->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $message->fresh(),
        ]);
    }
}
