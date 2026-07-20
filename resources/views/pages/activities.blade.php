@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="Activities" title="Our Statements &amp; Advocacy Record" description="A chronological record of MAWIE's public statements, commemorations, and engagements - a plainspoken account of what we've raised our voice on, and when." />

<section>
    <div class="wrap">
        <div class="archive-list">
            @foreach($archiveEntries as $entry)
                <div class="archive-entry">
                    <div class="archive-left">
                        <x-media-placeholder class="archive-media" label="Photo coming soon" />
                    </div>
                    <div class="archive-text">
                        <div class="archive-tag">{{ $entry['tag'] }}<span class="sub">{{ $entry['sub'] }}</span></div>
                        <h3>{{ $entry['title'] }}</h3>
                        @foreach($entry['paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
