@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Media')" :title="__('Gallery')" :description="__('Photographs from events, commemorations, and meetings will be published here as they become available.')" media="true" />

<section>
    <div class="wrap" data-media-root>
        <div class="media-sub-nav">
            <button type="button" data-media-tab="videos" data-media-title="{{ __('Short Videos') }}" data-media-description="{{ __('Video statements, event footage, and interviews will be published here as they become available.') }}">{{ __('Short Videos') }}</button>
            <button type="button" class="active" data-media-tab="gallery" data-media-title="{{ __('Gallery') }}" data-media-description="{{ __('Photographs from events, commemorations, and meetings will be published here as they become available.') }}">{{ __('Gallery') }}</button>
        </div>

        <div class="media-grid" data-media-panel="videos" hidden>
            @forelse($videos as $video)
                <article
                    class="video-card video-card--interactive"
                    tabindex="0"
                    role="button"
                    aria-label="{{ __('Play :title', ['title' => $video->name]) }}"
                    data-video-item
                    data-video-link="{{ $video->video_link }}"
                >
                    <div class="video-card__media">
                        @if($video->thumbnail)
                            <img class="video-card__thumbnail" src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->name }}" loading="lazy">
                        @else
                            <x-media-placeholder class="video-card__placeholder" kind="video" :label="__('Watch video')" />
                        @endif
                        <span class="video-card__play" aria-hidden="true">▶</span>
                    </div>
                    <div class="video-card__body">
                        <strong>{{ $video->name }}</strong>
                    </div>
                </article>
            @empty
                <p class="media-empty">{{ __('No short videos are available yet.') }}</p>
            @endforelse
        </div>
        @if ($videos->hasPages())
            @php($videos->appends(['media' => 'videos']))
            <div class="archive-pagination media-pagination" data-media-panel="videos">
                <div class="pagination-strip" aria-label="{{ __('Short videos pagination') }}">
                    @if ($videos->onFirstPage())
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Prev') }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $videos->previousPageUrl() }}">{{ __('Prev') }}</a>
                    @endif

                    @php($videoStartPage = max(1, min($videos->currentPage() - 1, $videos->lastPage() - 2)))
                    @php($videoEndPage = min($videos->lastPage(), $videoStartPage + 2))
                    @for ($page = $videoStartPage; $page <= $videoEndPage; $page++)
                        @if ($page === $videos->currentPage())
                            <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $videos->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($videos->hasMorePages())
                        <a class="pagination-btn" href="{{ $videos->nextPageUrl() }}">{{ __('Next') }}</a>
                    @else
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Next') }}</span>
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
                        aria-label="{{ __('View :title', ['title' => $galery->name]) }}"
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
                        <x-media-placeholder class="gallery-card__placeholder" :label="__('Photo unavailable')" />
                    @endif

                    <div class="gallery-card__body">
                        <h2>{{ $galery->name }}</h2>
                    </div>
                </article>
            @empty
                <p class="media-empty">{{ __('No gallery photographs are available yet.') }}</p>
            @endforelse
        </div>
        @if ($galeries->hasPages())
            @php($galeries->appends(['media' => 'gallery']))
            <div class="archive-pagination media-pagination" data-media-panel="gallery">
                <div class="pagination-strip" aria-label="{{ __('Gallery pagination') }}">
                    @if ($galeries->onFirstPage())
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Prev') }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $galeries->previousPageUrl() }}">{{ __('Prev') }}</a>
                    @endif

                    @php($galleryStartPage = max(1, min($galeries->currentPage() - 1, $galeries->lastPage() - 2)))
                    @php($galleryEndPage = min($galeries->lastPage(), $galleryStartPage + 2))
                    @for ($page = $galleryStartPage; $page <= $galleryEndPage; $page++)
                        @if ($page === $galeries->currentPage())
                            <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $galeries->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($galeries->hasMorePages())
                        <a class="pagination-btn" href="{{ $galeries->nextPageUrl() }}">{{ __('Next') }}</a>
                    @else
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Next') }}</span>
                    @endif
                </div>
            </div>
        @endif

        <dialog class="video-dialog" data-video-dialog aria-label="{{ __('Video player') }}">
            <div class="video-dialog__inner">
                <button class="gallery-dialog__close" type="button" data-video-close aria-label="{{ __('Close video') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="video-dialog__frame">
                    <iframe data-video-dialog-frame src="" title="{{ __('Video player') }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </dialog>

        <dialog class="gallery-dialog" data-gallery-dialog aria-label="{{ __('Gallery image') }}">
            <div class="gallery-dialog__inner">
                <button class="gallery-dialog__close" type="button" data-gallery-close aria-label="{{ __('Close image') }}">
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
