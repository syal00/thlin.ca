<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('thlin.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) ?: '1' }}">
    @stack('head')
</head>
<body class="admin-body">
    @auth
        <div class="admin-shell">
            <aside class="admin-sidebar" id="admin-sidebar">
                <div class="admin-brand">
                    <div class="admin-logo">THL</div>
                    <div>
                        <strong>THLIN CMS</strong>
                        <span>Admin Panel</span>
                    </div>
                </div>

                <nav class="admin-nav">
                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Main</span>
                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.inline-editing') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.inline-editing') ? 'active' : '' }}">
                            Quick Website Edits
                        </a>
                        <a href="{{ url('/?edit=1') }}"
                           target="_blank"
                           rel="noopener"
                           class="admin-nav-link admin-nav-link-featured">
                            Open Website Editor
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Content</span>
                        <a href="{{ route('admin.pages.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            Pages
                        </a>
                        <a href="{{ route('admin.news.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                            News
                        </a>
                        <a href="{{ route('admin.careers.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">
                            Careers
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Organization</span>
                        <a href="{{ route('admin.board.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.board.*') ? 'active' : '' }}">
                            Board
                        </a>
                        <a href="{{ route('admin.portfolio.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                            Portfolio
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Files</span>
                        <a href="{{ route('admin.media.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                            Uploaded Files
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Settings</span>
                        <a href="{{ route('admin.users.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            Users
                        </a>
                    </div>
                </nav>

                <div class="admin-sidebar-footer">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-view-site">
                        View Website
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="admin-logout">
                            Log out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="admin-main">
                <header class="admin-topbar">
                    <div class="admin-topbar-intro">
                        <h1>
                            @hasSection('page_title')
                                @yield('page_title')
                            @elseif (View::hasSection('breadcrumb'))
                                @yield('breadcrumb')
                            @else
                                @yield('title', 'Dashboard')
                            @endif
                        </h1>
                        <p>@yield('page_subtitle', 'Manage website content and updates.')</p>
                    </div>

                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-primary admin-topbar-action">
                        View Website
                    </a>
                </header>

                <main class="admin-content">
                    @if (session('success'))
                        <div class="admin-alert admin-alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error') || session('status'))
                        <div class="admin-alert admin-alert-error">
                            {{ session('error') ?? session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="admin-alert admin-alert-error">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @hasSection('admin-content')
                        @yield('admin-content')
                    @else
                        @yield('content')
                    @endif
                </main>
            </div>
        </div>
    @else
        <main class="admin-content admin-content--guest">
            @yield('content')
        </main>
    @endauth

    @stack('scripts')
</body>
</html>
