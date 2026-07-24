@php($pageTitle = $pageTitle ?? null)
@php($metaDescription = $metaDescription ?? null)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3b1f3a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    @isset($metaDescription)
    <meta name="description" content="{{ $metaDescription }}">
    @endisset
    <title>{{ $pageTitle ? $pageTitle.' · AFGHANWOMENINEXILE' : 'The Movement of Afghan women in Exile (AFGHANWOMENINEXILE)' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('site.css') }}?v={{ filemtime(public_path('site.css')) }}">
    <script src="{{ asset('site.js') }}?v={{ filemtime(public_path('site.js')) }}" defer></script>
</head>
<body>
    @include('partials.header')
    <main>
        @yield('content')
    </main>
    <div class="motif on-dark"></div>
    @include('partials.footer')
</body>
</html>
