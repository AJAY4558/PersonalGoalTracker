{{-- Sidebar Partial --}}

<nav class="sidebar" id="sidebar">
    <button type="button" class="sidebar-collapse-toggle" id="sidebarCollapseToggle" title="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>

    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="brand-link" data-tooltip="Altair Lux" title="Altair Lux">
            <span class="brand-icon"></span>
            <span class="brand-text sidebar-label">Altair Lux</span>
            <span class="brand-subtitle sidebar-label">Elite Performance</span>
        </a>
        <button class="sidebar-toggle d-lg-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    @auth
    <div class="sidebar-user">
        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="sidebar-avatar">
        <div class="sidebar-user-info sidebar-label">
            <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
            <div class="sidebar-user-email">{{ Auth::user()->email }}</div>
        </div>
    </div>
    @endauth

    <ul class="sidebar-menu">
        @auth
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link" data-tooltip="{{ __('messages.nav.dashboard') }}" title="{{ __('messages.nav.dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-label">{{ __('messages.nav.dashboard') }}</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('goals.*') ? 'active' : '' }}">
            <a href="{{ route('goals.index') }}" class="menu-link" data-tooltip="{{ __('messages.nav.my_goals') }}" title="{{ __('messages.nav.my_goals') }}">
                <i class="bi bi-bullseye"></i>
                <span class="sidebar-label">{{ __('messages.nav.my_goals') }}</span>
            </a>
        </li>

        <li class="menu-item new-goal-item">
            <a href="{{ route('goals.create') }}" class="menu-link" data-tooltip="{{ __('messages.nav.new_goal') }}" title="{{ __('messages.nav.new_goal') }}">
                <i class="bi bi-plus-circle"></i>
                <span class="sidebar-label">{{ __('messages.nav.new_goal') }}</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('groups.*') ? 'active' : '' }}">
            <a href="{{ route('groups.index') }}" class="menu-link" data-tooltip="Groups" title="Groups">
                <i class="bi bi-people"></i>
                <span class="sidebar-label">Groups</span>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <a href="{{ route('profile') }}" class="menu-link" data-tooltip="{{ __('messages.nav.profile') }}" title="{{ __('messages.nav.profile') }}">
                <i class="bi bi-person-circle"></i>
                <span class="sidebar-label">{{ __('messages.nav.profile') }}</span>
            </a>
        </li>

        <li class="menu-divider"></li>

        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link" data-tooltip="{{ __('messages.nav.admin') }}" title="{{ __('messages.nav.admin') }}">
                <i class="bi bi-shield-lock"></i>
                <span class="sidebar-label">{{ __('messages.nav.admin') }}</span>
            </a>
        </li>

        <li class="menu-divider"></li>

        <li class="menu-item language-menu-item">
            <div class="lux-lang-switcher">
                <button type="button" class="lux-lang-trigger" id="languageTrigger" data-tooltip="{{ __('messages.language.switch') }}" title="{{ __('messages.language.switch') }}">
                    <i class="bi bi-translate"></i>
                    <span class="sidebar-label">{{ app()->getLocale() === 'hi' ? 'हि' : 'EN' }}</span>
                    <i class="bi bi-chevron-up sidebar-label"></i>
                </button>
                <div class="lux-lang-menu" id="languageMenu">
                    <a href="{{ route('locale.switch', 'en') }}">🇺🇸 <span>English</span></a>
                    <a href="{{ route('locale.switch', 'hi') }}">🇮🇳 <span>हिन्दी</span></a>
                </div>
            </div>
        </li>

        <li class="menu-divider"></li>

        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-link btn-logout" data-tooltip="{{ __('messages.nav.logout') }}" title="{{ __('messages.nav.logout') }}">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="sidebar-label">{{ __('messages.nav.logout') }}</span>
                </button>
            </form>
        </li>
        @endauth

        @guest
        <li class="menu-item">
            <a href="{{ route('login') }}" class="menu-link" data-tooltip="{{ __('messages.nav.login') }}" title="{{ __('messages.nav.login') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span class="sidebar-label">{{ __('messages.nav.login') }}</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('register') }}" class="menu-link" data-tooltip="{{ __('messages.nav.register') }}" title="{{ __('messages.nav.register') }}">
                <i class="bi bi-person-plus"></i>
                <span class="sidebar-label">{{ __('messages.nav.register') }}</span>
            </a>
        </li>
        @endguest
    </ul>
</nav>
