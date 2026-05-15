<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Altair — Built for Ambition. Set, track, and achieve your goals.">
    <title>@yield('title', 'Altair') — Altair</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,100..700,0..1,0&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    {{-- Custom CSS --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <script>
        if (localStorage.getItem('altairSidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    {{-- Sidebar + Main Content Layout --}}
    <div class="app-wrapper">

        {{-- SIDEBAR --}}
        @include('partials.sidebar')

        {{-- MAIN CONTENT AREA --}}
        <div class="main-content">

            {{-- TOP NAVBAR --}}
            @include('partials.navbar')

            {{-- PAGE CONTENT --}}
            <main class="content-area">
                {{-- Flash Messages --}}
                @include('partials.alerts')

                {{-- Page specific content injected here --}}
                @yield('content')
            </main>

            {{-- FOOTER --}}
            @include('partials.footer')

        </div>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    {{-- Global JS --}}
    <script>
        // Auto-dismiss alerts after 4 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-auto-dismiss').forEach(el => {
                let bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            });
        }, 4000);

        // Confirm before delete
        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', (e) => {
                if (!confirm(el.dataset.confirm || 'Are you sure?')) {
                    e.preventDefault();
                }
            });
        });

        const sidebarToggle = document.getElementById('sidebarCollapseToggle');
        sidebarToggle?.addEventListener('click', () => {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('altairSidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
        });

        const languageTrigger = document.getElementById('languageTrigger');
        const languageMenu = document.getElementById('languageMenu');
        languageTrigger?.addEventListener('click', (event) => {
            event.stopPropagation();
            languageMenu?.classList.toggle('open');
        });

        const notificationsTrigger = document.getElementById('notificationsTrigger');
        const notificationsPanel = document.getElementById('notificationsPanel');
        notificationsTrigger?.addEventListener('click', (event) => {
            event.stopPropagation();
            notificationsPanel?.classList.toggle('open');
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.lux-lang-switcher')) {
                languageMenu?.classList.remove('open');
            }
            if (!event.target.closest('#notificationsPanel') && !event.target.closest('#notificationsTrigger')) {
                notificationsPanel?.classList.remove('open');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
