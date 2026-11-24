@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="{{ $activity['tag'] }}" title="{{ $activity['title'] }}" description="{{ $activity['sub'] }}" />

<section class="tight activity-page">
    <div class="wrap">
        <div class="archive-entry archive-entry--detail activity-detail">
            <div class="activity-detail__media">
                @if(!empty($activity['image']))
                    <img class="activity-detail__image" src="{{ $activity['image'] }}" alt="{{ $activity['imageAlt'] ?? $activity['title'] }}" loading="lazy">
                @else
                    <x-media-placeholder class="activity-detail__placeholder" :label="__('Photo coming soon')" />
                @endif
            </div>
            <div class="archive-text">
                <div class="archive-tag">{{ $activity['tag'] }}<span class="sub" dir="auto">{{ $activity['sub'] }}</span></div>
                <h3 dir="auto">{{ $activity['title'] }}</h3>

                @foreach($activity['paragraphs'] as $paragraph)
                    <p dir="auto">{{ $paragraph }}</p>
                @endforeach

                <a class="archive-readmore" href="{{ localized_route('activities') }}">{{ __('Back to archive') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
