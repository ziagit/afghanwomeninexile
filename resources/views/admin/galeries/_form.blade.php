@php($isEdit = $galery->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.galeries.update', $galery) : route('admin.galeries.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $galery->name) }}" maxlength="25" required>
        <span class="form-note">Maximum 25 characters.</span>
        @error('name')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="details">Details</label>
        <textarea id="details" name="details" rows="8" required>{{ old('details', $galery->details) }}</textarea>
        @error('details')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="picture">Picture</label>
        <input id="picture" name="picture" type="file" accept="image/*">
        <span class="form-note">Maximum file size: 1 MB.</span>
        @if ($isEdit && $galery->picture)
            <div class="preview-row">
                <span>Current picture:</span>
                <a href="{{ asset('storage/'.$galery->picture) }}" target="_blank" rel="noreferrer">View file</a>
            </div>
        @endif
        @error('picture')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.galeries.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-brick">{{ $isEdit ? 'Update Galery' : 'Create Galery' }}</button>
    </div>
</form>
