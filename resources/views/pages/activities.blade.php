@extends('layouts.app')

@section('content')
@php
    if (! isset($activities)) {
        $activities = \App\Models\Activity::query()->orderBy('id')->paginate(10);
    }

    $activities->setCollection(
        $activities->getCollection()->map(function ($entry) {
            if (is_array($entry)) {
                return $entry;
            }

            $paragraphs = preg_split("/\R{2,}/", trim((string) $entry->body)) ?: [];
            $paragraphs = array_values(array_filter(array_map('trim', $paragraphs)));
            $excerptSource = implode(' ', array_slice($paragraphs, 0, 2));

            return [
                'id' => $entry->id,
                'tag' => \Illuminate\Support\Str::headline((string) $entry->type),
                'sub' => $entry->name ?: 'Advocacy',
                'title' => $entry->title,
                'paragraphs' => $paragraphs,
                'excerpt' => \Illuminate\Support\Str::limit($excerptSource, 240),
            ];
        })
    );
@endphp

<x-page-hero eyebrow="Activities" title="Our Statements &amp; Advocacy Record" description="A chronological record of MAWIE's public statements, commemorations, and engagements - a plainspoken account of what we've raised our voice on, and when." />

<section>
    <div class="wrap">
        <div class="archive-list">
            @foreach($activities as $entry)
                <div class="archive-entry">
                    <div class="archive-left">
                        <x-media-placeholder class="archive-media" label="Photo coming soon" />
                    </div>
                    <div class="archive-text">
                        <div class="archive-tag">{{ $entry['tag'] }}<span class="sub">{{ $entry['sub'] }}</span></div>
                        <h3 class="archive-title">{{ $entry['title'] }}</h3>
                        <p class="archive-excerpt">{{ $entry['excerpt'] }} <a class="archive-readmore-inline" href="{{ route('activities.show', $entry['id']) }}">Read more</a></p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="archive-pagination">
            @php
                $currentPage = $activities->currentPage();
                $lastPage = $activities->lastPage();
                $startPage = max(1, $currentPage - 1);
                $endPage = min($lastPage, $startPage + 2);
                $startPage = max(1, $endPage - 2);
            @endphp

            <div class="pagination-strip" aria-label="Activities pagination">
                @if ($currentPage > 1)
                    <a class="pagination-btn" href="{{ $activities->previousPageUrl() }}">Prev</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Prev</span>
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page === $currentPage)
                        <span class="pagination-btn is-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pagination-btn" href="{{ $activities->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($currentPage < $lastPage)
                    <a class="pagination-btn" href="{{ $activities->nextPageUrl() }}">Next</a>
                @else
                    <span class="pagination-btn is-disabled" aria-disabled="true">Next</span>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
