@extends('admin.layouts.app')

@section('title', 'Messages - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Contact Messages</h2>
    <p class="admin-desc">{{ $contacts->total() }} messages received</p>
</div>

<section class="admin-panel">
    <table class="admin-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td>{{ $contact->id }}</td>
                <td><strong>{{ $contact->name }}</strong></td>
                <td>{{ $contact->email }}</td>
                <td>{{ \Illuminate\Support\Str::limit($contact->subject ?? $contact->message, 60) }}</td>
                <td>{{ $contact->created_at->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No messages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $contacts->links() }}</div>
</section>
@endsection
