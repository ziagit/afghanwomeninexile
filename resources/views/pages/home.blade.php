@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="wrap">
        <span class="eyebrow">The Movement of Afghanistan Women in Exile</span>
        <h1 style="margin-top:16px">Standing for justice, equality, and human dignity.</h1>
        <div class="rule"></div>
        <p class="sub">We advance human rights, promote gender equality, and empower Afghan women in exile through peaceful advocacy, education, and international cooperation.</p>
        <div class="cta-row">
            <a class="btn btn-gold" href="{{ route('activities') }}">Read Our Statements</a>
            <a class="btn btn-outline" href="{{ route('contact') }}">Get Involved</a>
        </div>
    </div>
    <div class="wrap">
        <div class="ph on-dark hero-media">
            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
            <span>Photo placeholder - event or portrait image</span>
        </div>
    </div>
</section>

<div class="motif"></div>

<section class="dispatch-feature tight">
    <div class="wrap">
        <span class="eyebrow" style="color:var(--brick)">Featured Observance</span>
        <div class="dispatch-card dispatch-card--media" style="margin-top:22px">
            <div class="stamp">
                <span class="day">19</span>
                <span class="rest">May<br>Hazara<br>Culture Day</span>
            </div>
            <div>
                <h3>Hazara Culture Day</h3>
                <p>Observed annually on 19 May, Hazara Culture Day celebrates the rich cultural heritage, history, identity, and contributions of the Hazara people - and the languages, literature, arts, and historical memory that come with it.</p>
                <p>Protecting cultural heritage is inseparable from protecting human dignity, freedom of expression, and every community's right to preserve and share its identity without discrimination. Cultural diversity remains one of Afghanistan's greatest strengths.</p>
                <a class="more" href="{{ route('activities') }}">Read the full commemoration →</a>
            </div>
            <div class="ph">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                <span>Event photo placeholder</span>
            </div>
        </div>
    </div>
</section>

<section style="background:var(--parchment-dim)">
    <div class="wrap split">
        <div>
            <span class="eyebrow">Our Mission</span>
            <h2>A future of dignity, security, and equal opportunity.</h2>
            <a class="more" style="color:var(--brick); font-weight:600; font-size:.9rem" href="{{ route('about') }}">More about us →</a>
        </div>
        <div>
            <p>The Movement of Afghanistan Women in Exile was established in April 2025 by a coalition of human rights and women's rights advocates, united by a shared commitment to addressing the challenges faced by Afghan women under Taliban rule. With a focus on advocating for the legal and rightful protections of women, the movement raises awareness of their struggles, amplifies their voices, and fosters international support for their empowerment - through diplomatic engagement, collaboration, and education.</p>
            <div class="ph">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                <span>Photo placeholder</span>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="wrap">
        <div class="posts-head">
            <h2 style="font-size:1.8rem">Featured Posts</h2>
            <a style="font-size:.86rem; font-weight:600; color:var(--brick)" href="{{ route('activities') }}">View full archive →</a>
        </div>
        <div class="posts-grid">
            @foreach($featuredPosts as $post)
                <a class="post-card" href="{{ route('activities') }}">
                    <div class="ph">
                        @if($post['kind'] === 'video')
                            <span class="ph-play">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M10 8.5l6 3.5-6 3.5z" fill="currentColor"/></svg>
                            </span>
                            <span>Video placeholder</span>
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                            <span>Photo placeholder</span>
                        @endif
                    </div>
                    <span class="eyebrow">{{ $post['tag'] }}</span>
                    <h4>{{ $post['title'] }}</h4>
                    <p>{{ $post['text'] }}</p>
                    <span class="more">Read statement →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-band">
    <div class="wrap">
        <h2>Stand with us.</h2>
        <p>Every voice raised in support of Afghan women in exile strengthens the case for their protection, dignity, and future.</p>
        <a class="btn btn-gold" href="{{ route('contact') }}">Contact the Movement</a>
    </div>
</section>
@endsection
