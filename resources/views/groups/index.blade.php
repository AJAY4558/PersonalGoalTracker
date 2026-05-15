@extends('layouts.app')
@section('title', 'Groups')
@section('page-title', 'Groups Hub')

@section('content')
<div class="page-stack">
    <section class="obsidian-card groups-create-card">
        <div>
            <p class="lux-eyebrow">Create a Group</p>
            <h2>Launch a shared objective cell</h2>
        </div>
        <form method="POST" action="{{ route('groups.store') }}" class="groups-create-form">
            @csrf
            <div>
                <label for="name" class="form-label">Group Name</label>
                <input id="name" name="name" class="form-control recessed-input" value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" class="form-control recessed-input" rows="3">{{ old('description') }}</textarea>
            </div>
            <div>
                <label for="max_members" class="form-label">Max Members</label>
                <select id="max_members" name="max_members" class="form-select recessed-input">
                    @foreach([5,10,15,25,50] as $size)
                        <option value="{{ $size }}" @selected(old('max_members', 10) == $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary lux-indigo-btn">Create Group</button>
        </form>
        @if(session('success') && str_contains(session('success'), 'Invite code:'))
            <div class="invite-code-strip">
                <span>{{ Str::after(session('success'), 'Invite code: ') }}</span>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-copy="{{ Str::after(session('success'), 'Invite code: ') }}">Copy Invite Code</button>
            </div>
        @endif
    </section>

    <section>
        <h2 class="section-heading">My Groups</h2>
        @if($groups->isEmpty())
            <div class="empty-state obsidian-card">
                <i class="bi bi-people"></i>
                <p>No groups yet. Create one above or join with an invite code.</p>
            </div>
        @else
            <div class="groups-grid">
                @foreach($groups as $group)
                    @php
                        $members = $group->users;
                        $memberCount = $members->count();
                        $visibleMembers = $members->take(4);
                    @endphp
                    <article class="group-card obsidian-card">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <h3>{{ $group->name }}</h3>
                                <p>{{ $group->description ?: 'No description provided.' }}</p>
                            </div>
                            @if($group->created_by === Auth::id())
                                <span class="creator-pill">Creator</span>
                            @endif
                        </div>

                        <div class="avatar-stack mt-3">
                            @foreach($visibleMembers as $member)
                                <span title="{{ $member->name }}">{{ Str::upper(Str::substr($member->name, 0, 1)) }}</span>
                            @endforeach
                            @if($memberCount > 4)
                                <span>+{{ $memberCount - 4 }}</span>
                            @endif
                        </div>

                        <div class="group-progress">
                            <div class="d-flex justify-content-between">
                                <span>Average Completion</span>
                                <strong>{{ $group->average_progress }}%</strong>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $group->average_progress }}%"></div>
                            </div>
                        </div>

                        <div class="group-actions">
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-primary lux-indigo-btn">View Group</a>
                            <button type="button" class="btn btn-outline-secondary" data-copy="{{ route('groups.join-code') }}#{{ $group->invite_code }}">Invite Members</button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section class="obsidian-card join-group-card">
        <div>
            <p class="lux-eyebrow">Join a Group</p>
            <h2>Enter an invite code or paste a full invite link</h2>
        </div>
        <form method="POST" action="{{ route('groups.join-code') }}" class="join-group-form">
            @csrf
            <input name="invite_code" class="form-control recessed-input" placeholder="Invite code or /groups/join/... link" required>
            <button type="submit" class="btn btn-primary lux-indigo-btn">Join</button>
        </form>
    </section>
</div>

@push('scripts')
<script>
document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        const value = button.dataset.copy.split('#').pop();
        await navigator.clipboard?.writeText(value);
        button.textContent = 'Copied';
        setTimeout(() => button.textContent = button.classList.contains('btn-sm') ? 'Copy Invite Code' : 'Invite Members', 1600);
    });
});
</script>
@endpush
@endsection
