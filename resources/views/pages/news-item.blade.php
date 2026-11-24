@extends('layouts.app')

@section('content')
@php($title = $newsItem->translated('title'))
<x-page-hero :eyebrow="__('News')" :title="$title" :description="$newsItem->published_at->translatedFormat('j F Y')" />

<section class="tight activity-page">
    <div class="wrap">
        <div class="archive-entry archive-entry--detail activity-detail">
            <div class="activity-detail__media">
                @if($newsItem->image)
                    <img class="activity-detail__image" src="{{ asset('storage/'.$newsItem->image) }}" alt="{{ $title }}" loading="lazy">
                @else
                    <x-media-placeholder class="activity-detail__placeholder" :label="__('Photo coming soon')" />
                @endif
            </div>
            <div class="archive-text">
                <div class="archive-tag">{{ __('News') }}<span class="sub">{{ $newsItem->published_at->translatedFormat('j F Y') }}</span></div>
                <h3 dir="auto">{{ $title }}</h3>

                @foreach($newsItem->paragraphs() as $paragraph)
                    <p dir="auto">{{ $paragraph }}</p>
                @endforeach

                <a class="archive-readmore" href="{{ localized_route('media') }}">{{ __('Back to news') }}</a>
            </div>
        </div>
    </div>
</section>
@endsection
