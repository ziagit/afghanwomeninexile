@props(['kind' => 'image', 'label'])

<div {{ $attributes->class(['media-placeholder', 'media-placeholder--' . $kind]) }}>
    <div class="media-placeholder__icon" aria-hidden="true">
        @if($kind === 'video')
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 8.5l6 3.5-6 3.5z" fill="currentColor"/></svg>
        @else
            <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
        @endif
    </div>
    <span>{{ $label }}</span>
</div>
