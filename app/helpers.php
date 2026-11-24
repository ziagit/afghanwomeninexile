<?php

// Public routes are registered twice: "home" for English and "fa.home" for Dari (see routes/web.php).
if (! function_exists('localized_route')) {
    function localized_route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        return route($locale === 'fa' ? 'fa.'.$name : $name, $parameters);
    }
}

// The current page in the other language, keeping its parameters and query string.
if (! function_exists('switch_locale_url')) {
    function switch_locale_url(string $locale): string
    {
        $route = request()->route();
        $name = (string) $route?->getName();
        $name = str_starts_with($name, 'fa.') ? substr($name, 3) : $name;

        if ($name === '' || ! app('router')->has($name) || ! app('router')->has('fa.'.$name)) {
            return localized_route('home', [], $locale);
        }

        $url = localized_route($name, $route->parameters(), $locale);
        $query = request()->getQueryString();

        return $query ? $url.'?'.$query : $url;
    }
}
