<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /** POST /api/v1/contact  — public contact form submission */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'company'    => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:15',
            'query_type' => 'required|in:general,order,wholesale,press,other',
            'message'    => 'required|string|min:10|max:2000',
        ]);

        $contact = Contact::create($validated);

        // TODO: dispatch a mail notification event here
        // event(new ContactFormSubmitted($contact));

        return response()->json([
            'message' => 'Message received. We will respond within 24 hours.',
        ], 201);
    }

    /** GET /api/v1/contact  — admin only: list all submissions */
    public function index(Request $request): JsonResponse
    {
        $contacts = Contact::orderByDesc('created_at')
            ->paginate($request->input('per_page', 20));

        return response()->json($contacts);
    }

    /** PATCH /api/v1/contact/{id}/read  — admin mark as read */
    public function markRead(Contact $contact): JsonResponse
    {
        $contact->update(['is_read' => true]);
        return response()->json(['message' => 'Marked as read.']);
    }
}
