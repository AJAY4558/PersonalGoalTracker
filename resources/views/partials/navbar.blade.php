{{-- Top Navbar Partial --}}
<header class="top-navbar">
    <div class="navbar-left">
        {{-- Mobile sidebar toggle --}}
        <button class="sidebar-toggle-btn d-lg-none" id="sidebarOpen">
            <i class="bi bi-list"></i>
        </button>
        {{-- Breadcrumb / Page title --}}
        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="navbar-right">
        {{-- Search (quick) --}}
        @auth
        <a href="{{ route('goals.index') }}?search=" class="nav-icon-btn" title="Search Goals">
            <i class="bi bi-search"></i>
        </a>

        {{-- Notifications bell (demo) --}}
        <button class="nav-icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            @php
                $upcomingCount = Auth::user()->goals()
                    ->where('status', '!=', 'completed')
                    ->whereNotNull('deadline')
                    ->where('deadline', '<=', now()->addDays(3))
                    ->count();
            @endphp
            @if($upcomingCount > 0)
                <span class="badge-dot">{{ $upcomingCount }}</span>
            @endif
        </button>

        {{-- User dropdown --}}
        <div class="dropdown">
            <button class="user-dropdown-btn" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="nav-avatar">
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
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
