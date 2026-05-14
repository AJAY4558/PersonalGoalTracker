@extends('layouts.app')
@section('title', __('messages.dashboard.title'))
@section('page-title', __('messages.dashboard.title'))

@section('content')

{{-- Welcome Banner --}}
<div class="welcome-banner">
    <div>
        <h2>{{ __('messages.dashboard.welcome', ['name' => Auth::user()->name]) }}</h2>
        <p class="text-muted mb-0">{{ now()->format('l, d F Y') }}</p>
    </div>
    <a href="{{ route('goals.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-2"></i>{{ __('messages.goals.create') }}
    </a>
</div>

{{-- ── STATS CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-bullseye"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $totalGoals }}</div>
                <div class="stat-label">{{ __('messages.dashboard.total_goals') }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $completedGoals }}</div>
                <div class="stat-label">{{ __('messages.dashboard.completed') }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $inProgressGoals }}</div>
                <div class="stat-label">{{ __('messages.dashboard.in_progress') }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-trophy-fill"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $completionRate }}%</div>
                <div class="stat-label">{{ __('messages.dashboard.completion_rate') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── CHARTS ROW ──────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    {{-- Doughnut Chart: Goals by Status --}}
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-title">Goals by Status</h3>
            </div>
            <div class="chart-wrapper">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Bar Chart: Goals by Category --}}
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-title">Goals by Category</h3>
            </div>
            <div class="chart-wrapper">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Line Chart: Goals created per month --}}
    <div class="col-lg-4">
        <div class="chart-card">
            <div class="chart-card-header">
                <h3 class="chart-title">Monthly Trend</h3>
            </div>
            <div class="chart-wrapper">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ── BOTTOM ROW: Upcoming + Recent ──────────────────────────────────── --}}
<div class="row g-3">
    {{-- Upcoming Deadlines --}}
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3><i class="bi bi-calendar-event me-2"></i>{{ __('messages.dashboard.upcoming') }}</h3>
                <a href="{{ route('goals.index') }}?sort=deadline&order=asc" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="dash-card-body">
                @forelse($upcomingGoals as $goal)
                <div class="upcoming-goal-item">
                    <div class="upcoming-goal-info">
                        <a href="{{ route('goals.show', $goal) }}" class="upcoming-goal-title">{{ $goal->title }}</a>
                        <div class="upcoming-goal-meta">
                            <span class="badge {{ $goal->status_badge['class'] }}">{{ $goal->status_badge['label'] }}</span>
                            @if($goal->is_overdue)
                                <span class="text-danger fw-semibold"><i class="bi bi-exclamation-triangle"></i> Overdue!</span>
                            @else
                                <span class="text-muted">{{ $goal->days_remaining }} days left</span>
                            @endif
                        </div>
                    </div>
                    <div class="upcoming-deadline">
                        <i class="bi bi-calendar3"></i>
                        {{ $goal->deadline->format('d M') }}
                    </div>
                </div>
                @empty
                <div class="empty-state-sm">
                    <i class="bi bi-calendar-check"></i>
                    <p>No upcoming deadlines in next 7 days! 🎉</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Goals --}}
    <div class="col-lg-6">
        <div class="dash-card">
            <div class="dash-card-header">
                <h3><i class="bi bi-clock-history me-2"></i>{{ __('messages.dashboard.recent') }}</h3>
                <a href="{{ route('goals.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="dash-card-body">
                @forelse($recentGoals as $goal)
                <div class="recent-goal-item">
                    <div class="recent-goal-progress">
                        <div class="progress-ring" style="--pct:{{ $goal->progress }}">
                            <span>{{ $goal->progress }}%</span>
                        </div>
                    </div>
                    <div class="recent-goal-info">
                        <a href="{{ route('goals.show', $goal) }}" class="recent-goal-title">{{ $goal->title }}</a>
                        <div class="progress" style="height:6px">
                            <div class="progress-bar bg-primary" style="width:{{ $goal->progress }}%"></div>
                        </div>
                        @if($goal->category)
                            <small class="text-muted">{{ $goal->category->name }}</small>
                        @endif
                    </div>
                    <div>
                        <span class="badge {{ $goal->priority_badge['class'] }}">{{ $goal->priority_badge['label'] }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state-sm">
                    <i class="bi bi-bullseye"></i>
                    <p>No goals yet. <a href="{{ route('goals.create') }}">Create your first goal!</a></p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Chart.js Initialization ──────────────────────────────────────────────

// Status Doughnut Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
        datasets: [{
            data: [{{ $completedGoals }}, {{ $inProgressGoals }}, {{ $pendingGoals }}, {{ $cancelledGoals }}],
            backgroundColor: ['#4ec9b0', '#007acc', '#dcdcaa', '#f44747'],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#858585' } }
        },
        cutout: '70%',
    }
});

// Category Bar Chart
const catData = @json($goalsByCategory);
new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: catData.map(c => c.name),
        datasets: [{
            label: 'Goals',
            data: catData.map(c => c.total),
            backgroundColor: catData.map(c => c.color + 'cc'),
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#858585' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: '#858585', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
        }
    }
});

// Monthly Trend Line Chart
const trendData = @json($goalsByMonth);
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: trendData.map(d => d.month),
        datasets: [{
            label: 'Goals Created',
            data: trendData.map(d => d.total),
            borderColor: '#007acc',
            backgroundColor: 'rgba(0,122,204,0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#007acc',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#858585' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: '#858585', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
        }
    }
});
</script>
@endpush
