<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('thlin.name') }}</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/thlin-overrides.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
    <header class="admin-header">
        <div class="container">
            <strong><i class="bi bi-gear"></i> THLIN Admin</strong>
            @auth
                <nav class="admin-nav">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.inline-editing') }}">Inline Editing</a>
                    <a href="{{ route('admin.pages.index') }}">Pages</a>
                    <a href="{{ route('admin.news.index') }}">News</a>
                    <a href="{{ route('admin.careers.index') }}">Careers</a>
                    <a href="{{ route('admin.board.index') }}">Board</a>
                    <a href="{{ route('admin.portfolio.index') }}">Portfolio</a>
                    <a href="{{ route('admin.users.index') }}">Users</a>
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

        @auth
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @endauth

        @yield('content')
    </div>
</body>
</html>
