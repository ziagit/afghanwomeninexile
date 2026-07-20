@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Media" title="Gallery" description="Photographs from events, commemorations, and meetings will be published here as they become available." media="true" />

<section>
    <div class="wrap" data-media-root>
        <div class="media-sub-nav">
            <button type="button" class="active" data-media-tab="videos" data-media-title="Short Videos" data-media-description="Video statements, event footage, and interviews will be published here as they become available.">Short Videos</button>
            <button type="button" data-media-tab="gallery" data-media-title="Gallery" data-media-description="Photographs from events, commemorations, and meetings will be published here as they become available.">Gallery</button>
        </div>

        <div class="media-grid" data-media-panel="videos" hidden>
            @for($i = 0; $i < 6; $i++)
                <x-media-placeholder kind="video" label="Video coming soon" />
            @endfor
        </div>

        <div class="media-grid" data-media-panel="gallery">
            @for($i = 0; $i < 6; $i++)
                <x-media-placeholder label="Photo coming soon" />
            @endfor
        </div>
    </div>
</section>
@endsection
