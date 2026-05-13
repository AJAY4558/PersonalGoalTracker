{{-- Sidebar Partial --}}
{{-- Demonstrates: Blade partials, @auth, named routes, active link detection --}}

<nav class="sidebar" id="sidebar">
    {{-- Brand Logo --}}
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <span class="brand-icon">🎯</span>
            <span class="brand-text">GoalTracker</span>
        </a>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- User Avatar in Sidebar --}}
    @auth
    <div class="sidebar-user">
        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="sidebar-avatar">
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
    @endauth

    {{-- Navigation Menu --}}
    <ul class="sidebar-menu">
        @auth
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="bi bi-speedometer2"></i>
                <span>{{ __('messages.nav.dashboard') }}</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
            <a href="{{ route('goals.index') }}" class="menu-link">
                <i class="bi bi-bullseye"></i>
                <span>{{ __('messages.nav.my_goals') }}</span>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('goals.create') }}" class="menu-link">
                <i class="bi bi-plus-circle"></i>
                <span>{{ __('messages.nav.new_goal') }}</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <a href="{{ route('profile') }}" class="menu-link">
                <i class="bi bi-person-circle"></i>
                <span>{{ __('messages.nav.profile') }}</span>
            </a>
        </li>

        <li class="menu-divider"></li>

        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="bi bi-shield-lock"></i>
                <span>{{ __('messages.nav.admin') }}</span>
            </a>
        </li>

        <li class="menu-divider"></li>

        {{-- Language Switcher --}}
        <li class="menu-item">
            <div class="menu-link lang-switch-group">
                <i class="bi bi-translate"></i>
                <span>{{ __('messages.language.switch') }}</span>
            </div>
            <div class="lang-btns">
                <a href="{{ route('locale.switch', 'en') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-light' }}">EN</a>
                <a href="{{ route('locale.switch', 'hi') }}"
                   class="btn btn-sm {{ app()->getLocale() === 'hi' ? 'btn-primary' : 'btn-outline-light' }}">हि</a>
            </div>
        </li>

        <li class="menu-divider"></li>

        {{-- Logout --}}
        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-link btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>{{ __('messages.nav.logout') }}</span>
                </button>
            </form>
        </li>
        @endauth

        @guest
        <li class="menu-item">
            <a href="{{ route('login') }}" class="menu-link">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>{{ __('messages.nav.login') }}</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('register') }}" class="menu-link">
                <i class="bi bi-person-plus"></i>
                <span>{{ __('messages.nav.register') }}</span>
            </a>
        </li>
        @endguest
    </ul>
</nav>
