@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">

    {{-- Profile Card --}}
    <div class="col-lg-4">
        <div class="profile-card text-center">
            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar mb-3">
            <h2 class="profile-name">{{ $user->name }}</h2>
            <p class="profile-email">{{ $user->email }}</p>
            <p class="text-muted small">
                Member since {{ $user->created_at->format('d M Y') }}
            </p>
            {{-- Goal Stats --}}
            <div class="profile-stats mt-3">
                <div class="profile-stat">
                    <strong>{{ $user->goals()->count() }}</strong>
                    <small>Total Goals</small>
                </div>
                <div class="profile-stat">
                    <strong>{{ $user->goals()->where('status','completed')->count() }}</strong>
                    <small>Completed</small>
                </div>
                <div class="profile-stat">
                    <strong>{{ round($user->goals()->avg('progress') ?? 0) }}%</strong>
                    <small>Avg Progress</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">

        {{-- Update Profile Form --}}
        <div class="form-card mb-4">
            <h3 class="form-card-title"><i class="bi bi-person-fill me-2"></i>Update Profile</h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label for="avatar" class="form-label fw-semibold">Profile Photo</label>
                        <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror"
                            accept="image/*">
                        <small class="text-muted">Max 2MB — JPG, PNG, GIF</small>
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-save me-1"></i>Save Changes
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="form-card mb-4">
            <h3 class="form-card-title"><i class="bi bi-lock-fill me-2"></i>Change Password</h3>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label for="current_password" class="form-label fw-semibold">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="new_password" class="form-label fw-semibold">New Password</label>
                        <input type="password" id="new_password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-warning mt-3">
                    <i class="bi bi-shield-lock me-1"></i>Update Password
                </button>
            </form>
        </div>

        {{-- Language Preference --}}
        <div class="form-card">
            <h3 class="form-card-title"><i class="bi bi-translate me-2"></i>Language Preference</h3>
            <form method="POST" action="{{ route('profile.locale') }}">
                @csrf @method('PUT')
                <div class="d-flex gap-3 align-items-center">
                    <label class="form-label fw-semibold mb-0">Select Language:</label>
                    <div class="form-check">
                        <input type="radio" id="locale_en" name="locale" value="en" class="form-check-input"
                            {{ $user->locale === 'en' ? 'checked' : '' }}>
                        <label for="locale_en" class="form-check-label">🇬🇧 English</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="locale_hi" name="locale" value="hi" class="form-check-input"
                            {{ $user->locale === 'hi' ? 'checked' : '' }}>
                        <label for="locale_hi" class="form-check-label">🇮🇳 हिन्दी</label>
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
