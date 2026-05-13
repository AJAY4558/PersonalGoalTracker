@extends('layouts.app')
@section('title', __('messages.goals.edit') . ' — ' . $goal->title)
@section('page-title', __('messages.goals.edit'))

@section('content')
<div class="form-page-wrapper">
    <div class="form-card">
        <div class="form-card-header">
            <div>
                <h2>✏️ {{ __('messages.goals.edit') }}</h2>
                <p class="text-muted">Update: <strong>{{ $goal->title }}</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('goals.show', $goal) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-eye me-1"></i>View
                </a>
                <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        {{-- PUT /goals/{id} via method spoofing --}}
        <form method="POST" action="{{ route('goals.update', $goal) }}" enctype="multipart/form-data">
            @include('goals._form')  {{-- Shared form partial (detects $goal for prefill) --}}

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-warning btn-lg px-5">
                    <i class="bi bi-arrow-repeat me-2"></i>{{ __('messages.common.update') }} Goal
                </button>
                <a href="{{ route('goals.show', $goal) }}" class="btn btn-outline-secondary btn-lg">
                    {{ __('messages.common.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
