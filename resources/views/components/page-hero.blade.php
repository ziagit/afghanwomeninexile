@props(['eyebrow', 'title', 'description' => '', 'media' => false])

<section class="page-hero" @if($media) data-media-hero @endif>
    <div class="wrap">
        <span class="eyebrow">{{ $eyebrow }}</span>
        <h1 dir="auto" @if($media) data-media-title @endif>{{ $title }}</h1>
        @if($description)
            <p dir="auto" @if($media) data-media-description @endif>{{ $description }}</p>
        @endif
    </div>
</section>
