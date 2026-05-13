{{-- Goal Form Partial — Shared between create.blade.php and edit.blade.php --}}
{{-- Demonstrates: Blade @include, old(), CSRF, method field, validation --}}

@csrf
{{-- Method spoofing for PUT (edit form) --}}
@isset($goal)
    @method('PUT')
@endisset

<div class="row g-3">

    {{-- Title --}}
    <div class="col-12">
        <label for="title" class="form-label fw-semibold">
            {{ __('messages.goal.title') }} <span class="text-danger">*</span>
        </label>
        <input type="text" id="title" name="title"
            class="form-control form-control-lg @error('title') is-invalid @enderror"
            value="{{ old('title', $goal->title ?? '') }}"
            placeholder="e.g., Learn Laravel Framework"
            required>
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Description --}}
    <div class="col-12">
        <label for="description" class="form-label fw-semibold">{{ __('messages.goal.description') }}</label>
        <textarea id="description" name="description" rows="4"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Describe your goal in detail...">{{ old('description', $goal->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Category --}}
    <div class="col-md-6">
        <label for="category_id" class="form-label fw-semibold">{{ __('messages.goal.category') }}</label>
        <select id="category_id" name="category_id"
            class="form-select @error('category_id') is-invalid @enderror">
            <option value="">— Select Category —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('category_id', $goal->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Deadline --}}
    <div class="col-md-6">
        <label for="deadline" class="form-label fw-semibold">{{ __('messages.goal.deadline') }}</label>
        <input type="date" id="deadline" name="deadline"
            class="form-control @error('deadline') is-invalid @enderror"
            value="{{ old('deadline', isset($goal) && $goal->deadline ? $goal->deadline->format('Y-m-d') : '') }}"
            min="{{ date('Y-m-d') }}">
        @error('deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label for="status" class="form-label fw-semibold">{{ __('messages.goal.status') }} <span class="text-danger">*</span></label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(['pending','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ old('status', $goal->status ?? 'pending') === $s ? 'selected' : '' }}>
                    {{ __("messages.status.{$s}") }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Priority --}}
    <div class="col-md-4">
        <label for="priority" class="form-label fw-semibold">{{ __('messages.goal.priority') }} <span class="text-danger">*</span></label>
        <select id="priority" name="priority" class="form-select @error('priority') is-invalid @enderror" required>
            @foreach(['low','medium','high','critical'] as $p)
                <option value="{{ $p }}" {{ old('priority', $goal->priority ?? 'medium') === $p ? 'selected' : '' }}>
                    {{ __("messages.priority.{$p}") }}
                </option>
            @endforeach
        </select>
        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Progress --}}
    <div class="col-md-4">
        <label for="progress" class="form-label fw-semibold">
            {{ __('messages.goal.progress') }}: <span id="progress-value">{{ old('progress', $goal->progress ?? 0) }}%</span>
        </label>
        <input type="range" id="progress" name="progress"
            class="form-range @error('progress') is-invalid @enderror"
            min="0" max="100" step="5"
            value="{{ old('progress', $goal->progress ?? 0) }}"
            oninput="document.getElementById('progress-value').textContent = this.value + '%'">
        @error('progress') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Image / File Upload --}}
    <div class="col-12">
        <label for="image" class="form-label fw-semibold">{{ __('messages.goal.image') }}</label>

        @isset($goal)
            @if($goal->image_url)
            <div class="current-image mb-2">
                <img src="{{ $goal->image_url }}" alt="Current" style="max-height:100px;border-radius:8px">
                <small class="d-block text-muted mt-1">Current file — upload a new one to replace it</small>
            </div>
            @endif
        @endisset

        <input type="file" id="image" name="image"
            class="form-control @error('image') is-invalid @enderror"
            accept="image/*,.pdf,.doc,.docx"
            onchange="previewImage(this)">
        <div class="form-text">Max 5MB. Accepted: JPG, PNG, GIF, PDF, DOC</div>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

        {{-- Live image preview --}}
        <img id="image-preview" src="" alt="Preview" class="mt-2 d-none"
            style="max-height:120px;border-radius:8px;border:2px solid #6366f1">
    </div>

</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
