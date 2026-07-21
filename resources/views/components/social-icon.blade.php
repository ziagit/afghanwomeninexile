@props(['type'])

@if ($type === 'facebook')
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M14 8.5h2.5V6H14c-1.9 0-3.5 1.6-3.5 3.5V12H8v3h2.5v7h3v-7H16l.5-3H13.5V9.5c0-.6.4-1 1-1Z" fill="currentColor"/>
    </svg>
@elseif ($type === 'x')
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <path d="M4.5 5h4.1l4 5.4L17 5h2.5l-5.4 7.1L20 19h-4.1l-4.4-5.9L7 19H4.5l5.9-7.7L4.5 5Z" fill="currentColor"/>
    </svg>
@elseif ($type === 'youtube')
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <rect x="3" y="6.5" width="18" height="11" rx="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/>
        <path d="m10 9 5.5 3-5.5 3V9Z" fill="currentColor"/>
    </svg>
@elseif ($type === 'instagram')
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <rect x="4.5" y="4.5" width="15" height="15" rx="4" fill="none" stroke="currentColor" stroke-width="1.9"/>
        <circle cx="12" cy="12" r="3.4" fill="none" stroke="currentColor" stroke-width="1.9"/>
        <circle cx="16.8" cy="7.2" r="1.1" fill="currentColor"/>
    </svg>
@endif
