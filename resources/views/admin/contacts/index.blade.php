@extends('admin.layouts.app')

@section('title', 'Messages - NumNam Admin')

@section('content')
<div class="admin-page-header">
    <h2>Contact Messages</h2>
    <p class="admin-desc">{{ $contacts->total() }} messages &middot; {{ $unreadCount }} unread</p>
</div>

<section class="admin-panel">
    <form method="GET" class="admin-search-bar" style="padding:10px 12px;">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by name or email...">
        <label style="display:inline-flex; align-items:center; gap:4px; font-size:13px; white-space:nowrap;">
            <input type="checkbox" name="unread" value="1" @checked(request()->has('unread'))> Unread only
        </label>
        <button class="admin-btn" type="submit">Filter</button>
        @if(request('q') || request()->has('unread'))
        <a href="{{ route('admin.contacts.index') }}" class="admin-btn-secondary" style="text-decoration:none;">Clear</a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr style="{{ !$contact->is_read ? 'font-weight:600;' : '' }}">
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->first_name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ ucfirst($contact->query_type ?? '—') }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($contact->message, 50) }}</td>
                    <td>
                        @if($contact->is_read)
                        <span class="status-badge status-badge--active">Read</span>
                        @else
                        <span class="status-badge status-badge--pending">Unread</span>
                        @endif
                    </td>
                    <td>{{ $contact->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="admin-link">View</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" style="display:inline;" onsubmit="return confirm('Delete this message?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="admin-empty">No messages yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $contacts->links() }}</div>
</section>
@endsection