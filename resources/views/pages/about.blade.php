@extends('layouts.app')

@section('content')
<x-page-hero eyebrow="About" title="About Us" description="Who we are, what we work toward, and the principles that guide us." />

<section class="tight">
    <div class="wrap">
        @foreach($aboutBlocks as $block)
            <div class="value-block">
                <span class="eyebrow">{{ $block['eyebrow'] }}</span>
                <div>
                    <h3>{{ $block['title'] }}</h3>
                    <p>{{ $block['text'] }}</p>
                    @if($block['image'])
                        <div class="ph team-ph">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="1.8" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M7.2 14l2.6-2.6 2.2 2.2 3.2-3.8 3.8 4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="8" cy="9" r="1.2" fill="currentColor"/></svg>
                            <span>Team photo placeholder</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="founder">
    <div class="wrap founder-grid">
        <div class="portrait" style="background-image:url('/images/farzana.avif'); background-size:cover; background-position:center top; background-repeat:no-repeat">
            <div class="cap">
                <div class="name">Farzana Rezaei</div>
                <div class="role">Founder &amp; Director</div>
            </div>
        </div>
        <div>
            <span class="eyebrow">About the Founder</span>
            <blockquote class="pull" style="margin-top:22px">"No lasting change could happen without the conscious and active participation of women."</blockquote>
            <p class="bio">Farzana Rezaei, a woman of resilience, born in 1993, holds a bachelor's degree in law and has dedicated her life to defending justice, empowering women, and serving her community. Farzana, the founder and director of the logistics company "Banu Rezaei," became a shining example of women's ability to thrive in entrepreneurship, even under the difficult circumstances of Afghanistan.</p>
            <p class="bio">Alongside her professional success, Farzana founded the Afghan Women's Union - a platform for connection, support, and a strong voice for women who had long been marginalized. By establishing the Fayeq Institute of Higher Education, Farzana once again proved that education is the heartbeat of transformation. During the time of the Republic, she served as a legal advocate for three years and was actively involved in human rights and civil society.</p>
            <p class="bio">Forced into exile after the Taliban regained control of Afghanistan, Farzana Rezaei courageously stood at the forefront of women's and girls' protests. She condemned the Taliban's crimes, including targeted killings, systematic suppression of minorities, and the ban on girls' education. Now, even in exile, Farzana continues the path of advocacy - she founded the "Women's Rights and Human Rights Movement" and publishes a journal titled "Women in Exile."</p>
        </div>
    </div>
</section>
@endsection
