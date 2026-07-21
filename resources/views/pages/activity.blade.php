@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="{{ $activity['tag'] }}" title="{{ $activity['title'] }}" description="{{ $activity['sub'] }}" />

<section class="tight activity-page">
    <div class="wrap">
        <div class="archive-entry archive-entry--detail">
            <div class="archive-left">
                <x-media-placeholder class="archive-media" label="Photo coming soon" />
            </div>
            <div class="archive-text">
                <div class="archive-tag">{{ $activity['tag'] }}<span class="sub">{{ $activity['sub'] }}</span></div>
                <h3>{{ $activity['title'] }}</h3>

                @foreach($activity['paragraphs'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach

                <a class="archive-readmore" href="{{ route('activities') }}">Back to archive</a>
            </div>
        </div>
    </div>
</section>
@endsection
