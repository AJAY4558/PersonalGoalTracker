<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Personal Goal Tracker — Login and Register">
    <title>@yield('title', 'Welcome') — GoalTracker</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="auth-body">

    <div class="auth-wrapper">
        {{-- Language Switcher --}}
        <div class="lang-switcher-top">
            <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">EN</a>
            <a href="{{ route('locale.switch', 'hi') }}" class="btn btn-sm {{ app()->getLocale() === 'hi' ? 'btn-primary' : 'btn-outline-secondary' }}">HI</a>
        </div>

        {{-- Flash Messages --}}
        @include('partials.alerts')

        {{-- Auth Content --}}
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
