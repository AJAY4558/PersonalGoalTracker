<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $groups = Group::query()
            ->with(['users', 'tasks.assignments', 'creator'])
            ->where('created_by', $user->id)
            ->orWhereHas('members', fn ($query) => $query->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_members' => ['required', 'integer', 'min:2', 'max:50'],
        ]);

        $group = Group::create([
            ...$validated,
            'created_by' => Auth::id(),
            'invite_code' => $this->makeInviteCode(),
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'creator',
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('groups.index')
            ->with('success', "Group created. Invite code: {$group->invite_code}");
    }

    public function show(Group $group)
    {
        $this->authorizeGroupAccess($group);

        $group->load([
            'creator',
            'users',
            'members.user.groupTaskAssignments.task',
            'tasks.assigner',
            'tasks.assignments.user',
        ]);

        $userRole = $this->memberRole($group);

        return view('groups.show', compact('group', 'userRole'));
    }

    public function invite(Request $request, Group $group)
    {
        $this->authorizeGroupAdmin($group);

        $validated = $request->validate([
            'invited_email' => ['required', 'email', 'max:255'],
        ]);

        $invite = GroupInvite::create([
            'group_id' => $group->id,
            'invited_by' => Auth::id(),
            'invited_email' => $validated['invited_email'],
            'token' => Str::random(40),
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        $invitedUser = User::where('email', $validated['invited_email'])->first();
        if ($invitedUser) {
            AppNotification::create([
                'user_id' => $invitedUser->id,
                'type' => 'group_invite',
                'data' => [
                    'group_id' => $group->id,
                    'group_name' => $group->name,
                    'invite_id' => $invite->id,
                    'token' => $invite->token,
                    'invited_by' => Auth::user()->name,
                ],
            ]);
        }

        return back()->with('success', 'Invite created: ' . route('groups.join', $invite->token));
    }

    public function join(string $token)
    {
        $invite = GroupInvite::with('group')->where('token', $token)->firstOrFail();

        abort_if($invite->status !== 'pending', 410, 'This invite is no longer pending.');
        abort_if($invite->expires_at && $invite->expires_at->isPast(), 410, 'This invite has expired.');

        $this->addCurrentUserToGroup($invite->group);

        $invite->update(['status' => 'accepted']);

        AppNotification::where('user_id', Auth::id())
            ->where('type', 'group_invite')
            ->where('data->token', $token)
            ->update(['read_at' => now()]);

        return redirect()->route('groups.show', $invite->group)->with('success', 'You joined the group.');
    }

    public function joinCode(Request $request)
    {
        $validated = $request->validate([
            'invite_code' => ['required', 'string', 'max:255'],
        ]);

        $value = trim($validated['invite_code']);
        $token = Str::contains($value, '/groups/join/')
            ? Str::afterLast($value, '/groups/join/')
            : null;

        if ($token) {
            return $this->join($token);
        }

        $group = Group::where('invite_code', Str::upper($value))->firstOrFail();
        $this->addCurrentUserToGroup($group);

        return redirect()->route('groups.show', $group)->with('success', 'You joined the group.');
    }

    public function declineInvite(GroupInvite $invite)
    {
        abort_if($invite->invited_email !== Auth::user()->email, 403);

        $invite->update(['status' => 'declined']);

        AppNotification::where('user_id', Auth::id())
            ->where('type', 'group_invite')
            ->where('data->invite_id', $invite->id)
            ->update(['read_at' => now()]);

        return back()->with('info', 'Invite declined.');
    }

    public function leave(Group $group)
    {
        $membership = GroupMember::where('group_id', $group->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($membership->role === 'creator') {
            $nextAdmin = GroupMember::where('group_id', $group->id)
                ->where('user_id', '!=', Auth::id())
                ->orderByRaw("role = 'admin' desc")
                ->oldest('joined_at')
                ->first();

            if (!$nextAdmin) {
                return back()->with('error', 'Creators cannot leave an empty group. Delete or invite another member first.');
            }

            $nextAdmin->update(['role' => 'creator']);
            $group->update(['created_by' => $nextAdmin->user_id]);
        }

        $membership->delete();

        return redirect()->route('groups.index')->with('success', 'You left the group.');
    }

    private function addCurrentUserToGroup(Group $group): void
    {
        abort_if($group->users()->count() >= $group->max_members, 422, 'This group is full.');

        GroupMember::firstOrCreate(
            ['group_id' => $group->id, 'user_id' => Auth::id()],
            ['role' => 'member', 'joined_at' => now()]
        );
    }

    private function authorizeGroupAccess(Group $group): void
    {
        abort_unless(
            $group->created_by === Auth::id()
                || $group->members()->where('user_id', Auth::id())->exists(),
            403
        );
    }

    private function authorizeGroupAdmin(Group $group): void
    {
        abort_unless(in_array($this->memberRole($group), ['creator', 'admin'], true), 403);
    }

    private function memberRole(Group $group): ?string
    {
        return GroupMember::where('group_id', $group->id)
            ->where('user_id', Auth::id())
            ->value('role');
    }

    private function makeInviteCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (Group::where('invite_code', $code)->exists());

        return $code;
    }
}
