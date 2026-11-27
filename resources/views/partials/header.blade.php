@php($locale = app()->getLocale())
<header data-site-header>
    <div class="nav-wrap">
        <a href="{{ localized_route('home') }}" class="brand" aria-label="{{ __('AfghanWomenInExile Home') }}">
            <span class="mark"><img src="{{ asset('logo.png') }}" alt="" aria-hidden="true"></span>
            <span class="names">
                <span class="full">{{ __('AFGHAN WOMEN') }}</span>
                <span class="short">{{ __('In Exile') }}</span>
            </span>
        </a>

        <nav class="primary" data-primary-nav>
            <a class="toplink {{ request()->routeIs('home', 'fa.home') ? 'active' : '' }}" href="{{ localized_route('home') }}">{{ __('Home') }}</a>
            <a class="toplink {{ request()->routeIs('activities', 'fa.activities') ? 'active' : '' }}" href="{{ localized_route('activities') }}">{{ __('Activities') }}</a>
            <a class="toplink {{ request()->routeIs('media', 'media.show', 'fa.media', 'fa.media.show') ? 'active' : '' }}" href="{{ localized_route('media') }}">{{ __('Media') }}</a>
            <a class="toplink {{ request()->routeIs('about', 'fa.about') ? 'active' : '' }}" href="{{ localized_route('about') }}">{{ __('About') }}</a>
            <a class="toplink {{ request()->routeIs('contact', 'fa.contact') ? 'active' : '' }}" href="{{ localized_route('contact') }}">{{ __('Contact') }}</a>
            <a class="toplink header-donate {{ request()->routeIs('support', 'fa.support') ? 'active' : '' }}" href="{{ localized_route('support') }}">{{ __('Support Us') }}</a>
        </nav>

        <div class="nav-end">
            @php($languages = ['en' => ['name' => 'English', 'hreflang' => 'en'], 'fa' => ['name' => 'دری', 'hreflang' => 'fa-AF']])
            <div class="lang-menu" data-lang-menu>
                <button class="lang-menu__button" type="button" aria-haspopup="true" aria-expanded="false" aria-controls="lang-menu-list" aria-label="{{ __('Language') }}: {{ $languages[$locale]['name'] }}" data-lang-menu-button>
                    <svg class="lang-menu__globe" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3z" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                </button>
                <ul class="lang-menu__list" id="lang-menu-list" hidden data-lang-menu-list>
                    @foreach ($languages as $code => $language)
                        <li>
                            <a href="{{ switch_locale_url($code) }}" lang="{{ $code }}" hreflang="{{ $language['hreflang'] }}" @if ($code === $locale) aria-current="true" @endif>
                                <svg class="lang-menu__check" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                {{ $language['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button class="menu-toggle" type="button" aria-label="{{ __('Toggle navigation') }}" aria-expanded="false" data-nav-toggle>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</header>
