@extends('layouts.app')

@section('content')
<x-page-hero :eyebrow="__('Media')" :title="__('News')" :description="__('News and updates from the Movement of Afghan Women in Exile.')" />

<section>
    <div class="wrap">
        @if ($news->isEmpty())
            <p class="media-empty">{{ __('No news has been published yet.') }}</p>
        @else
            <div class="news-grid">
                @foreach($news as $newsItem)
                    @php
                        $isLead = $loop->first && $news->onFirstPage();
                        $title = $newsItem->translated('title');
                    @endphp
                    <a class="news-card{{ $isLead ? ' news-card--lead' : '' }}" href="{{ localized_route('media.show', $newsItem) }}">
                        <div class="news-card__media">
                            @if($newsItem->image)
                                <img src="{{ asset('storage/'.$newsItem->image) }}" alt="{{ $title }}" loading="{{ $isLead ? 'eager' : 'lazy' }}">
                            @else
                                <x-media-placeholder :label="__('Photo coming soon')" />
                            @endif
                        </div>
                        <div class="news-card__body">
                            <div class="news-card__meta">
                                @if($isLead)<span class="news-card__flag">{{ __('Latest') }}</span>@endif
                                <time datetime="{{ $newsItem->published_at->toDateString() }}">{{ $newsItem->published_at->translatedFormat('j F Y') }}</time>
                            </div>
                            <h3 class="news-card__title" dir="auto">{{ $title }}</h3>
                            <p class="news-card__excerpt" dir="auto">{{ \Illuminate\Support\Str::limit(implode(' ', array_slice($newsItem->paragraphs(), 0, 2)), $isLead ? 360 : 180) }}</p>
                            <span class="news-card__more">{{ __('Read more') }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

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
