@extends('layouts.app')
@section('title', __('messages.goals.title'))
@section('page-title', __('messages.goals.title'))

@section('content')

{{-- ── FILTER & SEARCH BAR ──────────────────────────────────────────────── --}}
<div class="goals-toolbar mb-4">
    <form method="GET" action="{{ route('goals.index') }}" id="filterForm" class="d-flex flex-wrap gap-2 align-items-center">

        {{-- Search --}}
        <div class="input-group" style="max-width:280px">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control" placeholder="{{ __('messages.goals.search') }}"
                value="{{ request('search') }}">
        </div>

        {{-- Status Filter --}}
        <select name="status" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">{{ __('messages.goals.all_status') }}</option>
            @foreach(['pending','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ __("messages.status.{$s}") }}
                </option>
            @endforeach
        </select>

        {{-- Category Filter --}}
        <select name="category" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">{{ __('messages.goals.all_cat') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        {{-- Priority Filter --}}
        <select name="priority" class="form-select" style="width:auto" onchange="this.form.submit()">
            <option value="">All Priorities</option>
            @foreach(['low','medium','high','critical'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>

        @if(request()->hasAny(['search','status','category','priority']))
            <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        @endif

        <div class="ms-auto">
            <a href="{{ route('goals.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i>{{ __('messages.goals.create') }}
            </a>
        </div>
    </form>
</div>

{{-- ── GOALS GRID ────────────────────────────────────────────────────────── --}}
@if($goals->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">🎯</div>
        <h3>{{ __('messages.goals.no_goals') }}</h3>
        <p>Start by creating your first goal!</p>
        <a href="{{ route('goals.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create a Goal
        </a>
    </div>
@else
    <div class="row g-3 mb-4">
        @foreach($goals as $goal)
        <div class="col-md-6 col-xl-4">
            <div class="goal-card {{ $goal->is_overdue ? 'goal-overdue' : '' }}">
                {{-- Goal Card Header --}}
                <div class="goal-card-header">
                    <div class="goal-card-badges">
                        <span class="badge {{ $goal->status_badge['class'] }}">{{ $goal->status_badge['label'] }}</span>
                        <span class="badge {{ $goal->priority_badge['class'] }}">{{ $goal->priority_badge['label'] }}</span>
                    </div>
                    @if($goal->is_overdue)
                        <span class="overdue-tag"><i class="bi bi-exclamation-triangle"></i> Overdue</span>
                    @endif
                </div>

                {{-- Goal Image --}}
                @if($goal->image_url)
                <div class="goal-card-image">
                    <img src="{{ $goal->image_url }}" alt="{{ $goal->title }}">
                </div>
                @endif

                {{-- Goal Body --}}
                <div class="goal-card-body">
                    <h3 class="goal-card-title">
                        <a href="{{ route('goals.show', $goal) }}">{{ $goal->title }}</a>
                    </h3>
                    @if($goal->description)
                        <p class="goal-card-desc">{{ Str::limit($goal->description, 80) }}</p>
                    @endif

                    {{-- Category --}}
                    @if($goal->category)
                        <span class="goal-category" style="background:{{ $goal->category->color }}22;color:{{ $goal->category->color }}">
                            <i class="bi {{ $goal->category->icon }}"></i> {{ $goal->category->name }}
                        </span>
                    @endif

                    {{-- Progress Bar --}}
                    <div class="goal-progress mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Progress</small>
                            <small class="fw-semibold">{{ $goal->progress }}%</small>
                        </div>
                        <div class="progress" style="height:8px">
                            <div class="progress-bar bg-primary" style="width:{{ $goal->progress }}%"
                                role="progressbar" aria-valuenow="{{ $goal->progress }}"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    {{-- Deadline --}}
                    <div class="goal-deadline mt-2">
                        <i class="bi bi-calendar3"></i>
                        @if($goal->deadline)
                            {{ $goal->deadline->format('d M Y') }}
                            @if($goal->is_overdue)
                                <span class="text-danger">— Overdue!</span>
                            @elseif($goal->days_remaining !== null)
                                <span class="text-muted">({{ $goal->days_remaining }} days left)</span>
                            @endif
                        @else
                            <span class="text-muted">No deadline set</span>
                        @endif
                    </div>
                </div>

                {{-- Goal Card Footer: Actions --}}
                <div class="goal-card-footer">
                    <a href="{{ route('goals.show', $goal) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('goals.edit', $goal) }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('goals.destroy', $goal) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                            data-confirm="Delete '{{ $goal->title }}'?">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $goals->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection
