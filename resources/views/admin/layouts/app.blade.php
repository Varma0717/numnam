<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NumNam Admin')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="{{ url('assets/admin/css/admin.css') }}">
</head>

<body class="admin-body">
    {{-- WP-style admin bar --}}
    <div id="wpadminbar" class="admin-bar">
        <div class="admin-bar-inner">
            <a href="{{ route('admin.dashboard') }}" class="admin-bar-logo">
                <span class="ab-icon">NN</span>
                NumNam
            </a>
            <div class="admin-bar-right">
                <button type="button" class="ab-link admin-menu-toggle" onclick="document.body.classList.toggle('sidebar-open')" aria-label="Toggle menu">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <a href="{{ route('store.home') }}" class="ab-link" target="_blank">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                    Visit Store
                </a>
                <span class="ab-separator"></span>
                <span class="ab-user">{{ Auth::user()->name ?? 'Admin' }}</span>
                <form method="POST" action="{{ route('admin.logout') }}" class="ab-logout">
                    @csrf
                    <button type="submit" class="ab-link">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-wrap">
        {{-- WP-style sidebar --}}
        <nav id="adminmenu" class="admin-sidebar">
            <ul class="admin-menu">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'current' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="14" y="14" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                            </svg>
                        </span>
                        Dashboard
                    </a>
                </li>
                <li class="menu-separator"></li>
                <li class="{{ request()->routeIs('admin.products*') ? 'current' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                <line x1="3" y1="6" x2="21" y2="6" />
                                <path d="M16 10a4 4 0 01-8 0" />
                            </svg>
                        </span>
                        Products
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.orders*') ? 'current' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                            </svg>
                        </span>
                        Orders
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.coupons*') ? 'current' : '' }}">
                    <a href="{{ route('admin.coupons.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 12 20 22 4 22 4 12" />
                                <rect x="2" y="7" width="20" height="5" />
                                <line x1="12" y1="22" x2="12" y2="7" />
                                <path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z" />
                                <path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z" />
                            </svg>
                        </span>
                        Coupons
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.referrals*') ? 'current' : '' }}">
                    <a href="{{ route('admin.referrals.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 00-3-3.87" />
                                <path d="M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </span>
                        Referrals
                    </a>
                </li>
                <li class="menu-separator"></li>
                <li class="{{ request()->routeIs('admin.customers*') ? 'current' : '' }}">
                    <a href="{{ route('admin.customers.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        Customers
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.categories*') ? 'current' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z" />
                            </svg>
                        </span>
                        Categories
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.blogs*') ? 'current' : '' }}">
                    <a href="{{ route('admin.blogs.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </span>
                        Blog Posts
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.contacts*') ? 'current' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                        </span>
                        Messages
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.subscriptions*') ? 'current' : '' }}">
                    <a href="{{ route('admin.subscriptions.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                            </svg>
                        </span>
                        Subscriptions
                    </a>
                </li>
                <li class="menu-separator"></li>
                <li class="{{ request()->routeIs('admin.media') ? 'current' : '' }}">
                    <a href="{{ route('admin.media') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                        </span>
                        Media
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.settings*') ? 'current' : '' }}">
                    <a href="{{ route('admin.settings.index') }}">
                        <span class="menu-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3" />
                                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                            </svg>
                        </span>
                        Settings
                    </a>
                </li>
            </ul>
            <div class="admin-sidebar-footer">
                <button type="button" class="collapse-btn" onclick="document.body.classList.toggle('sidebar-collapsed')">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="11 17 6 12 11 7" />
                        <polyline points="18 17 13 12 18 7" />
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Main content --}}
        <div class="admin-content-wrap">
            <main class="admin-main">
                @if(session('status'))
                <div class="admin-notice admin-notice-success">
                    <p>{{ session('status') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="admin-notice admin-notice-error">
                    <p>{{ $errors->first() }}</p>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>