<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactManagementController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->q, fn($q) => $q->where(function ($sub) use ($request) {
                $sub->where('first_name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%");
            }))
            ->when($request->has('unread'), fn($q) => $q->where('is_read', false))
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        $unreadCount = Contact::where('is_read', false)->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(Contact $contact)
    {
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('status', 'Message deleted.');
    }
}
