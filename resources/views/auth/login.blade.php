@extends('layouts.guest')
@section('title', __('messages.auth.login'))

@section('content')
<div class="auth-card-wrapper">
    <div class="auth-card">
        {{-- Header --}}
        <div class="auth-card-header">
            <div class="auth-logo">🎯</div>
            <h2>{{ __('messages.auth.login') }}</h2>
            <p>Elite Performance</p>
        </div>

        {{-- Login Form --}}
        {{-- Demonstrates: CSRF, old input, validation, named routes, redirects --}}
        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf {{-- CSRF Protection Token --}}

            {{-- Email Field --}}
            <div class="form-group mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-1"></i> {{ __('messages.auth.email') }}
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"  {{-- Repopulates email on error --}}
                    placeholder="you@example.com"
                    required
                    autocomplete="email"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password Field --}}
            <div class="form-group mb-3">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i> {{ __('messages.auth.password') }}
                </label>
                <div class="input-password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePass('password')">
                        <i class="bi bi-eye" id="eye-password"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="form-check mb-3">
                <input type="checkbox" id="remember" name="remember" class="form-check-input"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">{{ __('messages.auth.remember') }}</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary w-100 btn-auth">
                <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('messages.auth.login') }}
            </button>
        </form>

        {{-- Register Link --}}
        <div class="auth-footer">
            {{ __('messages.auth.no_account') }}
            <a href="{{ route('register') }}">{{ __('messages.auth.register') }}</a>
        </div>
    </div>
</div>

<script>
function togglePass(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('eye-' + id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection
