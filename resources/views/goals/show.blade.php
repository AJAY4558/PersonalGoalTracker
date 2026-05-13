@extends('layouts.app')
@section('title', $goal->title)
@section('page-title', 'Goal Details')

@section('content')
<div class="goal-detail-wrapper">

    {{-- Header --}}
    <div class="goal-detail-header mb-4">
        <div class="goal-detail-title-row">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge {{ $goal->status_badge['class'] }} fs-6">{{ $goal->status_badge['label'] }}</span>
                    <span class="badge {{ $goal->priority_badge['class'] }}">{{ $goal->priority_badge['label'] }}</span>
                    @if($goal->is_overdue)
                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Overdue</span>
                    @endif
                </div>
                <h1 class="goal-detail-name">{{ $goal->title }}</h1>
                @if($goal->category)
                    <span class="goal-category-pill" style="background:{{ $goal->category->color }}22;color:{{ $goal->category->color }}">
                        <i class="bi {{ $goal->category->icon }}"></i> {{ $goal->category->name }}
                    </span>
                @endif
            </div>
            <div class="goal-detail-actions">
                <a href="{{ route('goals.edit', $goal) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
                <form method="POST" action="{{ route('goals.destroy', $goal) }}" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        data-confirm="Permanently delete '{{ $goal->title }}'?">
                        <i class="bi bi-trash me-1"></i>Delete
                    </button>
                </form>
                <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Main Content --}}
        <div class="col-lg-8">

            {{-- Description --}}
            @if($goal->description)
            <div class="detail-card mb-4">
                <h3 class="detail-section-title"><i class="bi bi-text-paragraph me-2"></i>Description</h3>
                <p class="goal-description">{{ $goal->description }}</p>
            </div>
            @endif

            {{-- Progress --}}
            <div class="detail-card mb-4">
                <h3 class="detail-section-title"><i class="bi bi-graph-up me-2"></i>Progress</h3>
                <div class="d-flex align-items-center gap-3">
                    <div class="progress flex-grow-1" style="height:20px;border-radius:10px">
                        <div class="progress-bar bg-primary fw-semibold" style="width:{{ $goal->progress }}%;border-radius:10px"
                            role="progressbar">{{ $goal->progress }}%</div>
                    </div>
                    <span class="progress-label">{{ $goal->progress }}%</span>
                </div>
            </div>

            {{-- Uploaded Image --}}
            @if($goal->image_url)
            <div class="detail-card mb-4">
                <h3 class="detail-section-title"><i class="bi bi-paperclip me-2"></i>Attachment</h3>
                <img src="{{ $goal->image_url }}" alt="Goal Attachment"
                    class="img-fluid rounded" style="max-height:300px;object-fit:cover">
            </div>
            @endif

        </div>

        {{-- Sidebar Info --}}
        <div class="col-lg-4">
            <div class="detail-card">
                <h3 class="detail-section-title"><i class="bi bi-info-circle me-2"></i>Details</h3>
                <ul class="detail-meta-list">
                    <li>
                        <span class="meta-key"><i class="bi bi-calendar3"></i> Deadline</span>
                        <span class="meta-value">
                            @if($goal->deadline)
                                {{ $goal->deadline->format('d M Y') }}
                                @if($goal->is_overdue)
                                    <br><small class="text-danger">Overdue!</small>
                                @elseif($goal->days_remaining !== null)
                                    <br><small class="text-muted">{{ $goal->days_remaining }} days remaining</small>
                                @endif
                            @else
                                <span class="text-muted">No deadline</span>
                            @endif
                        </span>
                    </li>
                    <li>
                        <span class="meta-key"><i class="bi bi-tag"></i> Category</span>
                        <span class="meta-value">{{ $goal->category?->name ?? '—' }}</span>
                    </li>
                    <li>
                        <span class="meta-key"><i class="bi bi-check2-circle"></i> Status</span>
                        <span class="meta-value">
                            <span class="badge {{ $goal->status_badge['class'] }}">{{ $goal->status_badge['label'] }}</span>
                        </span>
                    </li>
                    <li>
                        <span class="meta-key"><i class="bi bi-lightning"></i> Priority</span>
                        <span class="meta-value">
                            <span class="badge {{ $goal->priority_badge['class'] }}">{{ $goal->priority_badge['label'] }}</span>
                        </span>
                    </li>
                    @if($goal->completed_at)
                    <li>
                        <span class="meta-key"><i class="bi bi-trophy"></i> Completed</span>
                        <span class="meta-value text-success">{{ $goal->completed_at->format('d M Y') }}</span>
                    </li>
                    @endif
                    <li>
                        <span class="meta-key"><i class="bi bi-clock"></i> Created</span>
                        <span class="meta-value">{{ $goal->created_at->format('d M Y') }}</span>
                    </li>
                    <li>
                        <span class="meta-key"><i class="bi bi-arrow-repeat"></i> Updated</span>
                        <span class="meta-value">{{ $goal->updated_at->diffForHumans() }}</span>
                    </li>
                </ul>
            </div>

            {{-- Quick Status Update --}}
            <div class="detail-card mt-3">
                <h3 class="detail-section-title"><i class="bi bi-speedometer me-2"></i>Quick Update</h3>
                <form method="POST" action="{{ route('goals.update', $goal) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="title" value="{{ $goal->title }}">
                    <input type="hidden" name="description" value="{{ $goal->description }}">
                    <input type="hidden" name="priority" value="{{ $goal->priority }}">
                    <input type="hidden" name="deadline" value="{{ $goal->deadline?->format('Y-m-d') }}">
                    <input type="hidden" name="category_id" value="{{ $goal->category_id }}">

                    <label class="form-label fw-semibold">Progress: <span id="qp-val">{{ $goal->progress }}%</span></label>
                    <input type="range" name="progress" class="form-range mb-3" min="0" max="100" step="5"
                        value="{{ $goal->progress }}"
                        oninput="document.getElementById('qp-val').textContent=this.value+'%'">

                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending','in_progress','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $goal->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-save me-1"></i>Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
