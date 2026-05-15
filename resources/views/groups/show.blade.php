@extends('layouts.app')
@section('title', $group->name)
@section('page-title', $group->name)

@section('content')
@php
    $canManage = in_array($userRole, ['creator', 'admin'], true);
    $membersWithProgress = $group->members->map(function ($member) use ($group) {
        $assignmentIds = $group->tasks->pluck('id');
        $assignments = $member->user->groupTaskAssignments->whereIn('group_task_id', $assignmentIds);
        $member->overall_progress = $assignments->isEmpty() ? 0 : round($assignments->avg('progress'));
        return $member;
    })->sortByDesc('overall_progress');
@endphp

<div class="page-stack">
    <section class="obsidian-card group-detail-hero">
        <div>
            <p class="lux-eyebrow">Group Detail</p>
            <h2>{{ $group->name }}</h2>
            <p>{{ $group->description ?: 'No description provided.' }}</p>
        </div>
        <div class="group-hero-meta">
            <span>{{ $group->users->count() }} members</span>
            <span class="creator-pill">{{ ucfirst($userRole) }}</span>
        </div>
    </section>

    <div class="group-detail-layout">
        <aside class="obsidian-card members-panel">
            <h3>Members</h3>
            <div class="member-list">
                @foreach($membersWithProgress as $member)
                    <div class="member-row">
                        <div class="member-avatar">{{ Str::upper(Str::substr($member->user->name, 0, 1)) }}</div>
                        <div class="member-main">
                            <div class="d-flex justify-content-between gap-2">
                                <strong>{{ $member->user->name }}</strong>
                                <span class="role-pill">{{ ucfirst($member->role) }}</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $member->overall_progress }}%"></div>
                            </div>
                            <small>{{ $member->overall_progress }}% completion</small>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($canManage)
                <form method="POST" action="{{ route('groups.invite', $group) }}" class="invite-inline-form">
                    @csrf
                    <label class="form-label" for="invited_email">Invite by Email</label>
                    <input id="invited_email" name="invited_email" type="email" class="form-control recessed-input" placeholder="teammate@example.com" required>
                    <button class="btn btn-outline-secondary w-100" type="submit">Invite Members</button>
                </form>
            @endif
        </aside>

        <section class="tasks-panel">
            @if($canManage)
                <div class="obsidian-card assign-task-card">
                    <h3>Assign New Task</h3>
                    <form method="POST" action="{{ route('groups.tasks.store', $group) }}" class="assign-task-form">
                        @csrf
                        <div>
                            <label for="title" class="form-label">Task Title</label>
                            <input id="title" name="title" class="form-control recessed-input" required>
                        </div>
                        <div>
                            <label for="task_description" class="form-label">Description</label>
                            <textarea id="task_description" name="description" class="form-control recessed-input" rows="3"></textarea>
                        </div>
                        <div>
                            <label class="form-label">Priority</label>
                            <div class="priority-choice-row">
                                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical'] as $value => $label)
                                    <label class="priority-choice {{ $value }}">
                                        <input type="radio" name="priority" value="{{ $value }}" @checked($value === 'medium')>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label for="deadline" class="form-label">Deadline</label>
                            <input id="deadline" name="deadline" type="datetime-local" class="form-control recessed-input">
                        </div>
                        <div>
                            <label class="form-label">Assign To</label>
                            <label class="assign-check">
                                <input type="checkbox" name="assign_all" value="1">
                                <span>All Members</span>
                            </label>
                            <div class="assignee-grid">
                                @foreach($group->members as $member)
                                    <label class="assign-check">
                                        <input type="checkbox" name="assignees[]" value="{{ $member->user_id }}">
                                        <span>{{ $member->user->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <button class="btn btn-primary lux-indigo-btn shimmer-btn" type="submit">Deploy Task</button>
                    </form>
                </div>
            @endif

            <div class="task-list">
                @forelse($group->tasks as $task)
                    @php
                        $myAssignment = $task->assignments->firstWhere('user_id', Auth::id());
                        $deadlineClass = $task->deadline && $task->deadline->isPast() ? 'urgent-red' : (($task->days_remaining !== null && $task->days_remaining <= 3) ? 'urgent-orange' : 'urgent-primary');
                    @endphp
                    <article class="obsidian-card group-task-card">
                        <div class="task-card-header">
                            <div>
                                <h3>{{ $task->title }}</h3>
                                <p>{{ $task->description }}</p>
                            </div>
                            <span class="priority-badge {{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                        </div>
                        <div class="task-deadline {{ $deadlineClass }}">
                            <i class="bi bi-calendar3"></i>
                            @if($task->deadline)
                                {{ $task->deadline->format('d M Y, h:i A') }}
                                <span>{{ $task->days_remaining }} days remaining</span>
                            @else
                                No deadline
                            @endif
                        </div>

                        <div class="assigned-members">
                            @foreach($task->assignments as $assignment)
                                <div class="assigned-row">
                                    <div class="member-avatar">{{ Str::upper(Str::substr($assignment->user->name, 0, 1)) }}</div>
                                    <div class="member-main">
                                        <div class="d-flex justify-content-between gap-2">
                                            <strong>{{ $assignment->user->name }}</strong>
                                            <span class="status-pill">{{ ucfirst(str_replace('_', ' ', $assignment->status)) }}</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $assignment->progress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($myAssignment)
                            <form method="POST" action="{{ route('groups.tasks.progress', [$group, $task]) }}" class="my-progress-form">
                                @csrf
                                @method('PATCH')
                                <label class="form-label">Update My Progress</label>
                                <input type="range" name="progress" min="0" max="100" step="5" value="{{ $myAssignment->progress }}">
                                <select name="status" class="form-select recessed-input">
                                    @foreach(['pending', 'in_progress', 'completed'] as $status)
                                        <option value="{{ $status }}" @selected($myAssignment->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-outline-secondary" type="submit">Update My Progress</button>
                            </form>
                        @endif

                        @if($canManage && $task->status !== 'cancelled')
                            <form method="POST" action="{{ route('groups.tasks.cancel', [$group, $task]) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Mark Cancelled</button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="empty-state obsidian-card">
                        <i class="bi bi-check2-circle"></i>
                        <p>No tasks assigned yet.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
