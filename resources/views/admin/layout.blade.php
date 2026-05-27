<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('thlin.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="container">
            <strong>THLIN Admin</strong>
            @auth
                <nav class="admin-nav">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.pages.index') }}">Pages</a>
                    <a href="{{ route('admin.news.index') }}">News</a>
                    <a href="{{ route('admin.careers.index') }}">Careers</a>
                    <a href="{{ route('admin.board.index') }}">Board</a>
                    <a href="{{ route('admin.portfolio.index') }}">Portfolio</a>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener">View site</a>
                    <form action="{{ route('admin.logout') }}" method="post" class="admin-logout">
                        @csrf
                        <button type="submit">Log out</button>
                    </form>
                </nav>
            @endauth
        </div>
    </header>

    <div class="container admin-main">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
