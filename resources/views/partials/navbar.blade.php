{{-- Top Navbar Partial --}}
<header class="top-navbar">
    <div class="navbar-left">
        {{-- Mobile sidebar toggle --}}
        <button class="sidebar-toggle-btn d-lg-none" id="sidebarOpen">
            <i class="bi bi-list"></i>
        </button>
        {{-- Breadcrumb / Page title --}}
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        <div class="system-status d-none d-xl-inline-flex">
            <span class="system-status-dot active-pulse"></span>
            <span>System Status: Operational</span>
        </div>
    </div>

    <div class="navbar-right">
        {{-- Search (quick) --}}
        @auth
        <a href="{{ route('goals.index') }}?search=" class="topbar-search-shell" title="Search Goals">
            <i class="bi bi-search"></i>
            <span>Search goals</span>
        </a>

        <a href="{{ route('profile') }}" class="nav-icon-btn" title="Settings">
            <i class="bi bi-gear"></i>
        </a>

        {{-- Notifications bell (demo) --}}
        <button class="nav-icon-btn" id="notificationsTrigger" title="Notifications">
            <i class="bi bi-bell"></i>
            @if(($luxUnreadCount ?? 0) > 0)
                <span class="badge-dot">{{ $luxUnreadCount }}</span>
            @endif
        </button>
        <div class="notifications-panel" id="notificationsPanel">
            <div class="notifications-panel-header">
                <strong>Notifications</strong>
                <form method="POST" action="{{ route('notifications.mark-read') }}">
                    @csrf
                    <button type="submit">Mark all read</button>
                </form>
            </div>
            <div class="notifications-list">
                @forelse(($luxNotifications ?? collect()) as $notification)
                    @php($data = $notification->data ?? [])
                    <div class="notification-item {{ $notification->read_at ? 'is-read' : '' }}">
                        <i class="bi {{ $notification->icon ?? 'bi-bell' }}"></i>
                        <div>
                            <p>{{ $notification->message }}</p>
                            <small>{{ $notification->created_at?->diffForHumans() }}</small>
                            @if(($notification->type ?? '') === 'group_invite' && isset($data['token'], $data['invite_id']))
                                <div class="notification-actions">
                                    <a href="{{ route('groups.join', $data['token']) }}">Accept</a>
                                    <form method="POST" action="{{ route('groups.invites.decline', $data['invite_id']) }}">
                                        @csrf
                                        <button type="submit">Decline</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="notification-empty">
                        <i class="bi bi-bell-slash"></i>
                        <span>No new notifications</span>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- User dropdown --}}
        <div class="dropdown">
            <button class="user-dropdown-btn" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="nav-avatar">
                <span class="d-none d-md-inline">
                    {{ Auth::user()->name }}
                    <span class="user-role">Architect</span>
                </span>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="bi bi-person me-2"></i> Profile
                </a></li>
                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
    </div>
</header>

<script>
    // Mobile sidebar toggle
    document.getElementById('sidebarOpen')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.add('open');
    });
    document.getElementById('sidebarClose')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.remove('open');
    });
</script>
