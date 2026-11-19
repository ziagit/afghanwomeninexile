<header data-site-header>
    <div class="nav-wrap">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ __('AfghanWomenInExile Home') }}">
            <span class="mark"><img src="{{ asset('logo.png') }}" alt="" aria-hidden="true"></span>
            <span class="names">
                <span class="full">{{ __('AFGHAN WOMEN') }}</span>
                <span class="short">{{ __('In Exile') }}</span>
            </span>
        </a>

        <button class="menu-toggle" type="button" aria-label="{{ __('Toggle navigation') }}" aria-expanded="false" data-nav-toggle>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>

        <nav class="primary" data-primary-nav>
            <a class="toplink {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
            <a class="toplink {{ request()->routeIs('activities') ? 'active' : '' }}" href="{{ route('activities') }}">{{ __('Activities') }}</a>
            <a class="toplink {{ request()->routeIs('media') ? 'active' : '' }}" href="{{ route('media') }}">{{ __('Media') }}</a>
            <a class="toplink {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('About') }}</a>
            <a class="toplink {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('Contact') }}</a>
            <a class="toplink header-donate {{ request()->routeIs('support') ? 'active' : '' }}" href="{{ route('support') }}">{{ __('Donate Now') }}</a>
        </nav>
    </div>
</header>
