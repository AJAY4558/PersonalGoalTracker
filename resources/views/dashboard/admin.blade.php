@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="welcome-banner">
    <div>
        <h2>⚙️ Admin Dashboard</h2>
        <p class="text-muted">System-wide statistics and management</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-bullseye"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $stats['total_goals'] }}</div>
                <div class="stat-label">Total Goals</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-check2-all"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $stats['completed_goals'] }}</div>
                <div class="stat-label">Completed Goals</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card purple">
            <div class="stat-icon"><i class="bi bi-calendar-today"></i></div>
            <div class="stat-body">
                <div class="stat-num">{{ $stats['goals_today'] }}</div>
                <div class="stat-label">Goals Today</div>
            </div>
        </div>
    </div>
</div>

<div class="dash-card">
    <div class="dash-card-header">
        <h3><i class="bi bi-people me-2"></i>Recent Users</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Email</th><th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentUsers as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
