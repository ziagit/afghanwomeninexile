@php($isEdit = $verification->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.verifications.update', $verification) : route('admin.verifications.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="first_name">First name</label>
            <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $verification->first_name) }}" required>
            @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="last_name">Last name</label>
            <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $verification->last_name) }}" required>
            @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="form-grid">
        <div class="field">
            <label for="dob">Date of birth</label>
            <input id="dob" name="dob" type="date" value="{{ old('dob', optional($verification->dob)->format('Y-m-d')) }}" required>
            @error('dob')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="member_id">Member ID</label>
            <input id="member_id" name="member_id" type="text" value="{{ old('member_id', $verification->member_id) }}" required>
            @error('member_id')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="field">
        <label for="location">Location</label>
        <input id="location" name="location" type="text" value="{{ old('location', $verification->location) }}" required>
        @error('location')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-grid">
        <div class="field">
            <label for="joined_at">Joined at</label>
            <input id="joined_at" name="joined_at" type="date" value="{{ old('joined_at', optional($verification->joined_at)->format('Y-m-d')) }}" required>
            @error('joined_at')<span class="field-error">{{ $message }}</span>@enderror
        </div>
        <div class="field">
            <label for="expirs_at">Expires at</label>
            <input id="expirs_at" name="expirs_at" type="date" value="{{ old('expirs_at', optional($verification->expirs_at)->format('Y-m-d')) }}">
            @error('expirs_at')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="field">
        <label for="note">Note</label>
        <textarea id="note" name="note" rows="6">{{ old('note', $verification->note) }}</textarea>
        @error('note')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="field">
        <label for="picture">Picture</label>
        <input id="picture" name="picture" type="file" accept="image/*">
        <span class="form-note">Maximum file size: 1 MB.</span>
        @if ($isEdit && $verification->picture)
            <div class="preview-row">
                <span>Current picture:</span>
                <a href="{{ asset('storage/'.$verification->picture) }}" target="_blank" rel="noreferrer">View file</a>
            </div>
        @endif
        @error('picture')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.verifications.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-brick">{{ $isEdit ? 'Update Member' : 'Create Member' }}</button>
    </div>
</form>
