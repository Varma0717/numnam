@extends('admin.layouts.app')

@section('title', 'Referrals - Admin')

@section('content')
<section class="admin-panel">
    <h3>Referrers</h3>
    <table class="admin-table">
        <thead>
        <tr><th>User</th><th>Email</th><th>Code</th><th>Referrals</th></tr>
        </thead>
        <tbody>
        @forelse($referrers as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->referral_code }}</td>
                <td>{{ $user->referrals_count }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No referrers found.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $referrers->links() }}
</section>

<section class="admin-panel">
    <h3>Add Reward Adjustment</h3>
    <form method="POST" action="{{ route('admin.referrals.adjustment.store') }}" class="admin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr)); gap:.7rem; margin-bottom:.8rem;">
        @csrf
        <select name="user_id" required>
            <option value="">Select user</option>
            @foreach($referrers as $user)
                <option value="{{ $user->id }}">{{ $user->email }} ({{ $user->referral_code }})</option>
            @endforeach
        </select>
        <select name="type" required>
            <option value="credit">Credit</option>
            <option value="debit">Debit</option>
        </select>
        <input name="amount" type="number" min="1" step="0.01" placeholder="Amount" required>
        <input name="description" placeholder="Description" required>
        <button class="admin-btn" type="submit">Create Adjustment</button>
    </form>

    <h3>Reward Ledger</h3>
    <table class="admin-table">
        <thead>
        <tr><th>Date</th><th>User</th><th>Type</th><th>Amount</th><th>Description</th><th>Action</th></tr>
        </thead>
        <tbody>
        @forelse($rewardEntries as $entry)
            <tr>
                <td>{{ $entry->created_at->format('d M Y H:i') }}</td>
                <td>{{ $entry->user?->email }}</td>
                <td>{{ strtoupper($entry->type) }}</td>
                <td>Rs {{ number_format($entry->amount, 2) }}</td>
                <td>{{ $entry->description }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.referrals.reward.destroy', $entry) }}">
                        @csrf
                        @method('DELETE')
                        <button style="border:0;background:none;color:#b93434;cursor:pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No reward entries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $rewardEntries->links() }}
</section>
@endsection
