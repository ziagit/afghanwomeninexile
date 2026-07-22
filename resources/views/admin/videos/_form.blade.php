@php($isEdit = $video->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.videos.update', $video) : route('admin.videos.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $video->name) }}" maxlength="25" required>
        <span class="form-note">Maximum 25 characters.</span>
        @error('name')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="video_link">Video link</label>
        <input id="video_link" name="video_link" type="url" value="{{ old('video_link', $video->video_link) }}" required>
        @error('video_link')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="details">Details</label>
        <textarea id="details" name="details" rows="8" required>{{ old('details', $video->details) }}</textarea>
        @error('details')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="thumbnail">Thumbnail</label>
        <input id="thumbnail" name="thumbnail" type="file" accept="image/*">
        <span class="form-note">Maximum file size: 1 MB.</span>
        @if ($isEdit && $video->thumbnail)
            <div class="preview-row">
                <span>Current thumbnail:</span>
                <a href="{{ asset('storage/'.$video->thumbnail) }}" target="_blank" rel="noreferrer">View file</a>
            </div>
        @endif
        @error('thumbnail')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.videos.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-brick">{{ $isEdit ? 'Update Video' : 'Create Video' }}</button>
    </div>
</form>
