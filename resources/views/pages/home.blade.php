@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="wrap">
        <span class="eyebrow">{{ __('The Movement of Afghan women in Exile') }}</span>
        <h1 style="margin-top:16px">{{ __('Standing for justice, equality, and human dignity.') }}</h1>
        <div class="rule"></div>
        <p class="sub">{{ __('We advance human rights, promote gender equality, and empower Afghan women in exile through peaceful advocacy, education, and international cooperation.') }}</p>
        <div class="cta-row">
            <a class="btn btn-gold" href="{{ localized_route('activities') }}">{{ __('Read Our Statements') }}</a>
            <a class="btn btn-outline" href="{{ localized_route('contact') }}">{{ __('Get Involved') }}</a>
        </div>
    </div>
    <div class="wrap">
        <div class="ph on-dark hero-media hero-media--frame">
            <img src="{{ asset('images/justice-for-herat.avif') }}" alt="{{ __('Justice for Herat') }}" class="hero-media__img">
        </div>
    </div>
</section>

<div class="motif"></div>

<section class="dispatch-feature tight">
    <div class="wrap">
        <span class="eyebrow" style="color:var(--brick)">{{ __('Featured Observance') }}</span>
        <div class="dispatch-card dispatch-card--media" style="margin-top:22px">
            @php
                $observanceBody = !empty($featuredObservance['paragraphs'])
                    ? implode(' ', $featuredObservance['paragraphs'])
                    : __('No recent activity is available yet.');
            @endphp
            <div class="stamp">
                <span class="day">{{ $featuredObservance['stampDay'] ?? __('Latest') }}</span>
                <span class="rest" dir="auto">{{ $featuredObservance['stampRest'] ?? __('Activity') }}</span>
            </div>
            <div>
                <h3 dir="auto">{{ $featuredObservance['eventName'] ?? __('No recent activity available') }}</h3>
                <p class="featured-observance-body" dir="auto" style="display:-webkit-box;-webkit-line-clamp:8;-webkit-box-orient:vertical;overflow:hidden;line-height:1.55;max-height:12.4em;">{{ $observanceBody }}</p>
                <a class="more" href="{{ $featuredObservance['link'] ?? localized_route('activities') }}">{{ __('Read the full commemoration →') }}</a>
            </div>
            <div class="ph dispatch-media">
                @if(!empty($featuredObservance['image']))
                    <img src="{{ $featuredObservance['image'] }}" alt="{{ $featuredObservance['imageAlt'] ?? __('Featured observance') }}" class="dispatch-media__img">
                @else
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                    <span>{{ __('Event photo placeholder') }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

<section style="background:var(--parchment-dim)">
    <div class="wrap split">
        <div>
            <span class="eyebrow">{{ __('Our Mission') }}</span>
            <h2>{{ __('A future of dignity, security, and equal opportunity.') }}</h2>
            <a class="more" style="color:var(--brick); font-weight:600; font-size:.9rem" href="{{ localized_route('about') }}">{{ __('More about us →') }}</a>
        </div>
        <div>
            <p>{{ __('The Movement of Afghan women in Exile was established in April 2025 by a coalition of human rights and women\'s rights advocates, united by a shared commitment to addressing the challenges faced by Afghan women under Taliban rule. With a focus on advocating for the legal and rightful protections of women, the movement raises awareness of their struggles, amplifies their voices, and fosters international support for their empowerment - through diplomatic engagement, collaboration, and education.') }}</p>
            <div class="ph mission-media">
                <img src="{{ asset('images/equality.avif') }}" alt="{{ __('Equality') }}" class="mission-media__img">
            </div>
        </div>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="posts-head">
            <h2 style="font-size:1.8rem">{{ __('Featured Posts') }}</h2>
            <a style="font-size:.86rem; font-weight:600; color:var(--brick)" href="{{ localized_route('activities') }}">{{ __('View full archive →') }}</a>
        </div>
        <div class="posts-grid">
            @foreach($featuredPosts as $post)
                <a class="post-card" href="{{ $post['link'] ?? localized_route('activities') }}">
                    <div class="ph{{ !empty($post['image']) ? ' post-card__media--image' : '' }}">
                        @if($post['kind'] === 'video')
                            <span class="ph-play">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 8.5l6 3.5-6 3.5z" fill="currentColor"/></svg>
                            </span>
                            <span>{{ __('Video placeholder') }}</span>
                        @else
                            @if(!empty($post['image']))
                                <img src="{{ $post['image'] }}" alt="{{ $post['imageAlt'] ?? $post['title'] }}" class="post-card__media-img" loading="lazy">
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                                <span>{{ __('Photo placeholder') }}</span>
                            @endif
                        @endif
                    </div>
                    <span class="eyebrow">{{ $post['tag'] }}</span>
                    <h4 dir="auto">{{ $post['title'] }}</h4>
                    <p class="featured-post-body" dir="auto">{{ $post['text'] }}</p>
                    <span class="more">{{ __('Read statement →') }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

@if ($latestActivity)
<section class="latest-event-section">
    <div class="wrap">
        <div class="posts-head latest-event-head">
            <h2 style="font-size:1.8rem">{{ __('Latest activity') }}</h2>
            <a class="latest-event-all" href="{{ localized_route('activities') }}">{{ __('View all activities') }} <span aria-hidden="true">→</span></a>
        </div>
        <article class="latest-event-card">
            <div class="latest-event-media ph{{ $latestActivity['image'] ? ' latest-event-media--image' : '' }}" aria-label="{{ __('Photo placeholder') }}">
                <div class="latest-event-date">
                    <strong>{{ $latestActivity['date']?->format('d') }}</strong>
                    <span>{{ mb_strtoupper($latestActivity['date']?->translatedFormat('M')) }}</span>
                </div>
                @if ($latestActivity['image'])
                    <img src="{{ $latestActivity['image'] }}" alt="{{ $latestActivity['imageAlt'] }}" class="latest-event-image">
                @else
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                    <span>{{ __('Photo placeholder') }}</span>
                @endif
            </div>
            <div class="latest-event-details">
                <span class="eyebrow">{{ $latestActivity['tag'] }}</span>
                <h3 dir="auto">{{ $latestActivity['title'] }}</h3>
                <p class="latest-event-description" dir="auto">{{ $latestActivity['text'] }}</p>
                <a class="latest-event-recap" href="{{ localized_route('activities.show', $latestActivity['id']) }}">{{ __('Read recap') }} <span aria-hidden="true">→</span></a>
            </div>
        </article>
    </div>
</section>
@endif

<section class="cta-band">
    <div class="wrap">
        <h2>{{ __('Stand with Afghan women in exile.') }}</h2>
        <p>{{ __('Since losing their rights to education, work, and safety, Afghan women in exile are rebuilding their futures — one step at a time. Your donation helps provide shelter, legal support, and education so they can keep moving forward.') }}</p>
        <p><strong>{{ __('Your donation is a lifeline.') }}</strong></p>
        <a class="btn btn-gold" href="{{ localized_route('support') }}">{{ __('Support Us') }}</a>
    </div>
</section>
@endsection
