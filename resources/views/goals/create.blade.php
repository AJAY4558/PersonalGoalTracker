@extends('layouts.app')
@section('title', __('messages.goals.create'))
@section('page-title', __('messages.goals.create'))

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>🎯 {{ __('messages.goals.create') }}</h2>
                <p class="text-muted">Define a new goal and start tracking your progress</p>
            </div>
            <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back
            </a>
        </div>

        {{-- POST to /goals --}}
        <form method="POST" action="{{ route('goals.store') }}" enctype="multipart/form-data">
            @include('goals._form')  {{-- Shared form partial --}}

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 shimmer-btn">
                    <i class="bi bi-rocket-takeoff me-2"></i>DEPLOY OBJECTIVE
                </button>
                <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-power me-2"></i>Terminate Session
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
