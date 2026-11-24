@php($isEdit = $newsItem->exists)
<form class="admin-form" method="post" action="{{ $isEdit ? route('admin.news.update', $newsItem) : route('admin.news.store') }}" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="field">
            <label for="published_at">Date</label>
            <input id="published_at" name="published_at" type="date" value="{{ old('published_at', $newsItem->published_at?->format('Y-m-d')) }}" required>
            @error('published_at')<span class="field-error">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="lang-columns">
        <fieldset class="lang-fieldset">
            <legend>English</legend>
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $newsItem->title) }}" required>
                @error('title')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="body">Text</label>
                <textarea id="body" name="body" rows="10" required>{{ old('body', $newsItem->body) }}</textarea>
                <span class="form-note">Leave an empty line between paragraphs.</span>
                @error('body')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </fieldset>

        <fieldset class="lang-fieldset" lang="fa" dir="rtl">
            <legend>دری <span lang="en" dir="ltr">(Dari, optional)</span></legend>
            <div class="field">
                <label for="title_fa">عنوان</label>
                <input id="title_fa" name="title_fa" type="text" value="{{ old('title_fa', $newsItem->title_fa) }}">
                @error('title_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="body_fa">متن</label>
                <textarea id="body_fa" name="body_fa" rows="10">{{ old('body_fa', $newsItem->body_fa) }}</textarea>
                @error('body_fa')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <span class="form-note" lang="en" dir="ltr">Empty Dari fields show the English text on the Dari site.</span>
        </fieldset>
    </div>

    <div class="field">
        <label for="image">Image</label>
        <input id="image" name="image" type="file" accept="image/*">
        <span class="form-note">Maximum file size: 1 MB.</span>
        @if ($isEdit && $newsItem->image)
            <div class="preview-row">
                <span>Current image:</span>
                <a href="{{ asset('storage/'.$newsItem->image) }}" target="_blank" rel="noreferrer">View file</a>
            </div>
        @endif
        @error('image')<span class="field-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-brick">{{ $isEdit ? 'Update News' : 'Create News' }}</button>
    </div>
</form>
