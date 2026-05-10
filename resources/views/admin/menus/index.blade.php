@extends('admin.layouts.app')

@section('title', 'Menus - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Menus</h2>
        <p class="admin-desc">{{ $menus->total() }} menus</p>
    </div>
    <a href="{{ route('admin.menus.create') }}" class="admin-btn" style="text-decoration:none;">Add Menu</a>
</div>

<section class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Items</th>
                    <th>Status</th>
                    <th style="width:120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr>
                    <td>{{ $menu->id }}</td>
                    <td><strong>{{ $menu->name }}</strong></td>
                    <td>{{ $menu->location ?: '—' }}</td>
                    <td>{{ $menu->items_count }}</td>
                    <td>
                        <span class="status-badge status-badge--{{ $menu->is_active ? 'active' : 'paused' }}">
                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="admin-link">Edit</a>
                        <span style="color:var(--wp-border); margin:0 4px;">|</span>
                        <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" style="display:inline;" onsubmit="return confirm('Delete this menu and all its items?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="admin-empty">No menus found. <a href="{{ route('admin.menus.create') }}" class="admin-link">Create one</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:12px;">{{ $menus->links() }}</div>
</section>
@endsection