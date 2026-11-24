{{-- Ticked by default for new items; an item already on Facebook links to its post instead. --}}
<div class="field">
    @if ($item->facebook_post_id)
        <div class="preview-row">
            <span>Shared to Facebook:</span>
            <a href="https://www.facebook.com/{{ $item->facebook_post_id }}" target="_blank" rel="noreferrer">View post</a>
        </div>
    @else
        <label class="check-field" for="share_facebook">
            <input id="share_facebook" name="share_facebook" type="checkbox" value="1" @checked($errors->any() ? old('share_facebook') : ! $item->exists)>
            <span>{{ $item->exists ? 'Share to Facebook when saved' : 'Share to Facebook' }}</span>
        </label>
        @if ($item->facebook_error)
            <span class="field-error">Last attempt failed: {{ $item->facebook_error }}</span>
        @endif
    @endif
</div>
