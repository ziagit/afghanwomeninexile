@php($isEdit = $activity->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.activities.update', $activity) : route('admin.activities.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $activity->name) }}" required>
            @error('name')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="type">Type</label>
            <input id="type" name="type" type="text" value="{{ old('type', $activity->type) }}" required>
            @error('type')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="field">
        <label for="title">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $activity->title) }}" required>
        @error('title')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="body">Body</label>
        <textarea id="body" name="body" rows="8" required>{{ old('body', $activity->body) }}</textarea>
        @error('body')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="image">Image</label>
        <input id="image" name="image" type="file" accept="image/*">
        @if ($isEdit && $activity->image)
            <div class="preview-row">
                <span>Current image:</span>
                <a href="{{ asset('storage/'.$activity->image) }}" target="_blank" rel="noreferrer">View file</a>
            </div>
        @endif
        @error('image')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-brick">{{ $isEdit ? 'Update Activity' : 'Create Activity' }}</button>
    </div>
</form>
