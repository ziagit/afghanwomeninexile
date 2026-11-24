@php
    $isEdit = $activity->exists;
    $selectedType = old('type', $activity->type ?: 'general');
@endphp
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.activities.update', $activity) : route('admin.activities.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="type">Tag</label>
            <select id="type" name="type" required>
                <option value="general" @selected($selectedType === 'general')>General</option>
                <option value="event" @selected($selectedType === 'event')>Event</option>
                @if (! in_array($selectedType, ['general', 'event'], true))
                    <option value="{{ $selectedType }}" selected>{{ \Illuminate\Support\Str::headline($selectedType) }} (legacy)</option>
                @endif
            </select>
            @error('type')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="lang-columns">
        <fieldset class="lang-fieldset">
            <legend>English</legend>
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $activity->name) }}" required>
                @error('name')<span class="field-error">{{ $message }}</span>@enderror
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
        </fieldset>

        <fieldset class="lang-fieldset" lang="fa" dir="rtl">
            <legend>دری <span lang="en" dir="ltr">(Dari, optional)</span></legend>
            <div class="field">
                <label for="name_fa">نام</label>
                <input id="name_fa" name="name_fa" type="text" value="{{ old('name_fa', $activity->name_fa) }}">
                @error('name_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="title_fa">عنوان</label>
                <input id="title_fa" name="title_fa" type="text" value="{{ old('title_fa', $activity->title_fa) }}">
                @error('title_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="body_fa">متن</label>
                <textarea id="body_fa" name="body_fa" rows="8">{{ old('body_fa', $activity->body_fa) }}</textarea>
                @error('body_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <span class="form-note" lang="en" dir="ltr">Empty Dari fields show the English text on the Dari site.</span>
        </fieldset>
    </div>

    <div class="field">
        <label for="image">Image</label>
        <input id="image" name="image" type="file" accept="image/*">
        <span class="form-note">Maximum file size: 1 MB.</span>
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
