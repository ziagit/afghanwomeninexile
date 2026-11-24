@if ($item->facebook_post_id)
    <a href="https://www.facebook.com/{{ $item->facebook_post_id }}" target="_blank" rel="noreferrer">Shared</a>
@elseif ($item->facebook_error)
    <span class="field-error" title="{{ $item->facebook_error }}">Failed</span>
@else
    -
@endif
