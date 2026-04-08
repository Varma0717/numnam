@extends('admin.layouts.app')

@section('title', 'Settings - NumNam Admin')

@section('content')
<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px;">
    <div>
        <h2>Settings</h2>
        <p class="admin-desc">Manage your store configuration</p>
    </div>
    <a href="{{ route('admin.settings.create') }}" class="admin-btn-secondary" style="text-decoration:none;">Add Custom Setting</a>
</div>

{{-- WooCommerce-style Tabs --}}
<nav class="admin-tabs">
    @php
    $tabs = [
    'general' => ['label' => 'General', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        <polyline points="9 22 9 12 15 12 15 22" />
    </svg>'],
    'payment' => ['label' => 'Payments', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="1" y="4" width="22" height="16" rx="2" />
        <line x1="1" y1="10" x2="23" y2="10" />
    </svg>'],
    'shipping' => ['label' => 'Shipping', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <rect x="1" y="3" width="15" height="13" />
        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
        <circle cx="5.5" cy="18.5" r="2.5" />
        <circle cx="18.5" cy="18.5" r="2.5" />
    </svg>'],
    'tax' => ['label' => 'Tax', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="1" x2="12" y2="23" />
        <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
    </svg>'],
    'email' => ['label' => 'Email', 'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
        <polyline points="22,6 12,13 2,6" />
    </svg>'],
    ];
    @endphp
    @foreach($tabs as $key => $tab)
    <a href="{{ route('admin.settings.index', ['tab' => $key]) }}"
        class="admin-tab {{ $activeTab === $key ? 'admin-tab-active' : '' }}">
        {!! $tab['icon'] !!}
        {{ $tab['label'] }}
    </a>
    @endforeach
</nav>

{{-- Tab Content --}}
<div class="admin-tab-content">
    @include('admin.settings.tabs.' . $activeTab, ['settings' => $settings])
</div>
@endsection