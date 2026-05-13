@extends('layouts.guest')
@section('title', __('messages.auth.register'))

@section('content')
<div class="auth-card-wrapper">
    <div class="auth-card">
        <div class="auth-card-header">
            <div class="auth-logo">🎯</div>
            <h2>{{ __('messages.auth.register') }}</h2>
            <p>Start tracking your goals today — it's free!</p>
        </div>

        {{-- Registration Form --}}
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name" class="form-label">
                    <i class="bi bi-person me-1"></i> {{ __('messages.auth.name') }}
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    placeholder="John Doe"
                    required autocomplete="name"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group mb-3">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-1"></i> {{ __('messages.auth.email') }}
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required autocomplete="email"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
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
                        placeholder="Min. 8 characters"
                        required autocomplete="new-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePass('password')">
                        <i class="bi bi-eye" id="eye-password"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group mb-4">
                <label for="password_confirmation" class="form-label">
                    <i class="bi bi-lock-fill me-1"></i> {{ __('messages.auth.confirm_pass') }}
                </label>
                <div class="input-password-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter password"
                        required
                    >
                    <button type="button" class="toggle-password" onclick="togglePass('password_confirmation')">
                        <i class="bi bi-eye" id="eye-password_confirmation"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-auth">
                <i class="bi bi-person-plus me-2"></i>{{ __('messages.auth.register') }}
            </button>
        </form>

        <div class="auth-footer">
            {{ __('messages.auth.have_account') }}
            <a href="{{ route('login') }}">{{ __('messages.auth.login') }}</a>
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
