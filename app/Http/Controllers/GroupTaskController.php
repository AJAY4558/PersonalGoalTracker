<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupTask;
use App\Models\GroupTaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupTaskController extends Controller
{
    public function store(Request $request, Group $group)
    {
        $this->authorizeGroupAdmin($group);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'in:low,medium,high,critical'],
            'deadline' => ['nullable', 'date'],
            'assignees' => ['nullable', 'array'],
            'assignees.*' => ['integer', 'exists:users,id'],
            'assign_all' => ['nullable', 'boolean'],
        ]);

        $task = GroupTask::create([
            'group_id' => $group->id,
            'assigned_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'deadline' => $validated['deadline'] ?? null,
            'status' => 'pending',
        ]);

        $memberIds = $request->boolean('assign_all')
            ? $group->members()->pluck('user_id')->all()
            : ($validated['assignees'] ?? []);

        $memberIds = GroupMember::where('group_id', $group->id)
            ->whereIn('user_id', $memberIds)
            ->pluck('user_id')
            ->all();

        foreach ($memberIds as $userId) {
            GroupTaskAssignment::create([
                'group_task_id' => $task->id,
                'user_id' => $userId,
                'progress' => 0,
                'status' => 'pending',
            ]);

            if ($userId !== Auth::id()) {
                AppNotification::create([
                    'user_id' => $userId,
                    'type' => 'group_task_assigned',
                    'data' => [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'group_id' => $group->id,
                        'group_name' => $group->name,
                        'assigned_by' => Auth::user()->name,
                    ],
                ]);
            }
        }

        return back()->with('success', 'Group task deployed.');
    }

    public function updateProgress(Request $request, Group $group, GroupTask $task)
    {
        abort_unless($task->group_id === $group->id, 404);

        $assignment = GroupTaskAssignment::where('group_task_id', $task->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $assignment->update($validated);

        if ((int) $validated['progress'] === 100 || $validated['status'] === 'completed') {
            $assignment->update(['progress' => 100, 'status' => 'completed']);
        }

        return back()->with('success', 'Your task progress was updated.');
    }

    public function cancel(Group $group, GroupTask $task)
    {
        abort_unless($task->group_id === $group->id, 404);
        $this->authorizeGroupAdmin($group);

        $task->update(['status' => 'cancelled']);

        return back()->with('info', 'Task cancelled.');
    }

    private function authorizeGroupAdmin(Group $group): void
    {
        $role = GroupMember::where('group_id', $group->id)
            ->where('user_id', Auth::id())
            ->value('role');

        abort_unless(in_array($role, ['creator', 'admin'], true), 403);
    }
}
