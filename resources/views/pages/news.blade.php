@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Media')" :title="__('News')" :description="__('News and updates from the Movement of Afghan Women in Exile.')" />

<section>
    <div class="wrap">
        <div class="archive-list">
            @forelse($news as $newsItem)
                @php
                    $paragraphs = $newsItem->paragraphs();
                @endphp
                <div class="archive-entry">
                    <div class="archive-left">
                        @if($newsItem->image)
                            <img class="archive-media archive-media--image" src="{{ asset('storage/'.$newsItem->image) }}" alt="{{ $newsItem->translated('title') }}" loading="lazy">
                        @else
                            <x-media-placeholder class="archive-media" :label="__('Photo coming soon')" />
                        @endif
                    </div>
                    <div class="archive-text">
                        <div class="archive-tag">{{ __('News') }}<span class="sub">{{ $newsItem->published_at->translatedFormat('j F Y') }}</span></div>
                        <h3 class="archive-title" dir="auto">{{ $newsItem->translated('title') }}</h3>
                        <p class="archive-excerpt" dir="auto">{{ \Illuminate\Support\Str::limit(implode(' ', array_slice($paragraphs, 0, 2)), 240) }} <a class="archive-readmore-inline" href="{{ localized_route('media.show', $newsItem) }}">{{ __('Read more') }}</a></p>
                    </div>
                </div>
            @empty
                <p class="media-empty">{{ __('No news has been published yet.') }}</p>
            @endforelse
        </div>

        @if ($news->hasPages())
            <div class="archive-pagination">
                @php
                    $currentPage = $news->currentPage();
                    $lastPage = $news->lastPage();
                    $startPage = max(1, min($currentPage - 1, $lastPage - 2));
                    $endPage = min($lastPage, $startPage + 2);
                @endphp

                <div class="pagination-strip" aria-label="{{ __('News pagination') }}">
                    @if ($news->onFirstPage())
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Prev') }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $news->previousPageUrl() }}">{{ __('Prev') }}</a>
                    @endif

                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page === $currentPage)
                            <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-btn" href="{{ $news->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($news->hasMorePages())
                        <a class="pagination-btn" href="{{ $news->nextPageUrl() }}">{{ __('Next') }}</a>
                    @else
                        <span class="pagination-btn is-disabled" aria-disabled="true">{{ __('Next') }}</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
