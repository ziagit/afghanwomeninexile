@php($isEdit = $galery->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.galeries.update', $galery) : route('admin.galeries.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="lang-columns">
        <fieldset class="lang-fieldset">
            <legend>English</legend>
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
        </fieldset>

        <fieldset class="lang-fieldset" lang="fa" dir="rtl">
            <legend>دری <span lang="en" dir="ltr">(Dari, optional)</span></legend>
            <div class="field">
                <label for="name_fa">نام</label>
                <input id="name_fa" name="name_fa" type="text" value="{{ old('name_fa', $galery->name_fa) }}" maxlength="25">
                <span class="form-note" lang="en" dir="ltr">Maximum 25 characters.</span>
                @error('name_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="details_fa">جزئیات</label>
                <textarea id="details_fa" name="details_fa" rows="8">{{ old('details_fa', $galery->details_fa) }}</textarea>
                @error('details_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <span class="form-note" lang="en" dir="ltr">Empty Dari fields show the English text on the Dari site.</span>
        </fieldset>
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
