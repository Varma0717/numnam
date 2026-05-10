@extends('admin.layouts.app')

@section('title', 'Message from ' . $contact->first_name . ' - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Message from {{ $contact->first_name }}</h2>
        <p class="admin-desc">Received {{ $contact->created_at->format('d M Y \a\t H:i') }}</p>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('admin.contacts.index') }}" class="admin-btn-secondary" style="text-decoration:none;">&larr; Back</a>
        <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Delete this message?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger" style="padding:4px 10px;">Delete</button>
        </form>
    </div>
</div>

<section class="admin-panel" style="padding:20px;">
    <div style="display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; margin-bottom:20px;">
        <div>
            <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Name</label>
            <p style="margin:4px 0 0; font-size:14px;">{{ $contact->first_name }}</p>
        </div>
        <div>
            <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Email</label>
            <p style="margin:4px 0 0; font-size:14px;"><a href="mailto:{{ $contact->email }}" class="admin-link">{{ $contact->email }}</a></p>
        </div>
        @if($contact->phone)
        <div>
            <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Phone</label>
            <p style="margin:4px 0 0; font-size:14px;">{{ $contact->phone }}</p>
        </div>
        @endif
        @if($contact->company)
        <div>
            <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Company</label>
            <p style="margin:4px 0 0; font-size:14px;">{{ $contact->company }}</p>
        </div>
        @endif
        @if($contact->query_type)
        <div>
            <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Query Type</label>
            <p style="margin:4px 0 0; font-size:14px;">{{ ucfirst($contact->query_type) }}</p>
        </div>
        @endif
    </div>

    <div style="border-top:1px solid var(--wp-border); padding-top:16px;">
        <label style="font-size:12px; color:var(--wp-muted); text-transform:uppercase; letter-spacing:0.5px;">Message</label>
        <div style="margin-top:8px; font-size:14px; line-height:1.6; white-space:pre-wrap; background:#f6f7f7; padding:16px; border-radius:4px;">{{ $contact->message }}</div>
    </div>
</section>
@endsection