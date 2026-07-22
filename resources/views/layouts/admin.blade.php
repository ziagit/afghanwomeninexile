@php($pageTitle = $pageTitle ?? 'Admin')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3b1f3a">
    <title>{{ $pageTitle }} · AFGHANWOMENINEXILE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('site.css') }}">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div>
                <div class="admin-brand">DASHBOARD</div>
                <p class="admin-subtitle">Content administration</p>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.activities.index') }}" class="{{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">Activities</a>
                <a href="{{ route('admin.galeries.index') }}" class="{{ request()->routeIs('admin.galeries.*') ? 'active' : '' }}">Galeries</a>
                <a href="{{ route('admin.videos.index') }}" class="{{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">Videos</a>
                <a href="{{ route('admin.verifications.index') }}" class="{{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">Members</a>
                <a href="{{ route('home') }}">View site</a>
            </nav>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <span class="admin-kicker">Admin panel</span>
                    <h1>{{ $pageTitle }}</h1>
                </div>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-brick admin-logout">Logout</button>
                </form>
            </header>

            <main class="admin-content">
                @if (session('status'))
                    <div class="admin-alert">{{ session('status') }}</div>
                @endif
                @if (session('upload_error'))
                    <div class="admin-alert admin-alert--error">{{ session('upload_error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
