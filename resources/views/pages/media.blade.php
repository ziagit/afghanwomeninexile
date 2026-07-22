@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Media" title="Gallery" description="Photographs from events, commemorations, and meetings will be published here as they become available." media="true" />

<section>
    <div class="wrap" data-media-root>
        <div class="media-sub-nav">
            <button type="button" data-media-tab="videos" data-media-title="Short Videos" data-media-description="Video statements, event footage, and interviews will be published here as they become available.">Short Videos</button>
            <button type="button" class="active" data-media-tab="gallery" data-media-title="Gallery" data-media-description="Photographs from events, commemorations, and meetings will be published here as they become available.">Gallery</button>
        </div>

        <div class="media-grid" data-media-panel="videos" hidden>
            @forelse($videos as $video)
                <article
                    class="video-card video-card--interactive"
                    tabindex="0"
                    role="button"
                    aria-label="Play {{ $video->name }}"
                    data-video-item
                    data-video-link="{{ $video->video_link }}"
                >
                    <div class="video-card__link">
                        @if($video->thumbnail)
                            <img
                                class="video-card__thumbnail"
                                src="{{ asset('storage/' . $video->thumbnail) }}"
                                alt="{{ $video->name }}"
                                loading="lazy"
                            >
                        @else
                            <x-media-placeholder class="video-card__placeholder" kind="video" label="Watch video" />
                        @endif
                        <span class="video-card__play" aria-hidden="true">▶</span>
                        <span class="video-card__body">
                            <strong>{{ $video->name }}</strong>
                        </span>
                    </div>
                </article>
            @empty
                <p class="media-empty">No short videos are available yet.</p>
            @endforelse
        </div>
        @if ($videos->hasPages())
            @php($videos->appends(['media' => 'videos']))
            <div class="archive-pagination media-pagination" data-media-panel="videos">
                <div class="pagination-strip" aria-label="Short videos pagination">
                    @if ($videos->onFirstPage())
                        <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                    @else
                        <a class="pagination-btn" href="{{ $videos->previousPageUrl() }}">Prev</a>
                    @endif

                    @for ($page = 1; $page <= $videos->lastPage(); $page++)
                        @if ($page === $videos->currentPage())
                            <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $videos->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($videos->hasMorePages())
                        <a class="pagination-btn" href="{{ $videos->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                    @endif
                </div>
            </div>
        @endif

        <div class="media-grid" data-media-panel="gallery">
            @forelse($galeries as $galery)
                <article
                    class="gallery-card{{ $galery->picture ? ' gallery-card--interactive' : '' }}"
                    @if($galery->picture)
                        tabindex="0"
                        role="button"
                        aria-label="View {{ $galery->name }}"
                        data-gallery-item
                        data-gallery-image="{{ asset('storage/' . $galery->picture) }}"
                        data-gallery-title="{{ $galery->name }}"
                    @endif
                >
                    @if($galery->picture)
                        <img
                            class="gallery-card__image"
                            src="{{ asset('storage/' . $galery->picture) }}"
                            alt="{{ $galery->name }}"
                            loading="lazy"
                        >
                    @else
                        <x-media-placeholder class="gallery-card__placeholder" label="Photo unavailable" />
                    @endif

                    <div class="gallery-card__body">
                        <h2>{{ $galery->name }}</h2>
                    </div>
                </article>
            @empty
                <p class="media-empty">No gallery photographs are available yet.</p>
            @endforelse
        </div>
        @if ($galeries->hasPages())
            @php($galeries->appends(['media' => 'gallery']))
            <div class="archive-pagination media-pagination" data-media-panel="gallery">
                <div class="pagination-strip" aria-label="Gallery pagination">
                    @if ($galeries->onFirstPage())
                        <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                    @else
                        <a class="pagination-btn" href="{{ $galeries->previousPageUrl() }}">Prev</a>
                    @endif

                    @for ($page = 1; $page <= $galeries->lastPage(); $page++)
                        @if ($page === $galeries->currentPage())
                            <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $galeries->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($galeries->hasMorePages())
                        <a class="pagination-btn" href="{{ $galeries->nextPageUrl() }}">Next</a>
                    @else
                        <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                    @endif
                </div>
            </div>
        @endif

        <dialog class="video-dialog" data-video-dialog aria-label="Video player">
            <div class="video-dialog__inner">
                <button class="gallery-dialog__close" type="button" data-video-close aria-label="Close video">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="video-dialog__frame">
                    <iframe data-video-dialog-frame src="" title="Video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </dialog>

        <dialog class="gallery-dialog" data-gallery-dialog aria-label="Gallery image">
            <div class="gallery-dialog__inner">
                <button class="gallery-dialog__close" type="button" data-gallery-close aria-label="Close image">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="gallery-dialog__image-wrap">
                    <img class="gallery-dialog__image" data-gallery-dialog-image src="" alt="">
                </div>
            </div>
        </dialog>
    </div>
</section>
@endsection
