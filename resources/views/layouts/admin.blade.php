<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        (function () {
            var storageKey = 'thlin_admin_theme';
            var cookieName = 'thlin_admin_theme';
            var saved = null;

            try {
                saved = localStorage.getItem(storageKey);
            } catch (error) {
                saved = null;
            }

            if (!saved) {
                var match = document.cookie.match(/(?:^|;\s*)thlin_admin_theme=([^;]*)/);
                saved = match ? decodeURIComponent(match[1]) : null;
            }

            var theme = saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>
    <title>@yield('title', 'Admin') - {{ config('thlin.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.png') }}?v={{ @filemtime(public_path('favicon.png')) ?: '1' }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v={{ @filemtime(public_path('apple-touch-icon.png')) ?: '1' }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ @filemtime(public_path('css/admin.css')) ?: '1' }}">
    @stack('head')
</head>
<body class="admin-body">
    @auth
        <div class="admin-shell">
            <div class="admin-nav-backdrop" data-admin-nav-backdrop aria-hidden="true"></div>

            <aside class="admin-sidebar" id="admin-sidebar" aria-label="CMS navigation">
                <div class="admin-brand">
                    <div class="admin-logo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                    <div>
                        <strong>THLIN CMS</strong>
                        <span>Content workspace</span>
                    </div>
                </div>

                <nav class="admin-nav">
                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Overview</span>
                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                            </span>
                            Dashboard
                        </a>
                        <a href="{{ url('/?edit=1') }}"
                           target="_blank"
                           rel="noopener"
                           class="admin-nav-link admin-nav-link-featured"
                           data-admin-open-editor>
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            </span>
                            Open Website Editor
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Content</span>
                        <a href="{{ route('admin.pages.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </span>
                            Pages
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                            </span>
                            Site Settings
                        </a>
                        <a href="{{ route('admin.messages.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            Messages
                        </a>
                        <a href="{{ route('admin.news.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                            </span>
                            News
                        </a>
                        <a href="{{ route('admin.careers.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.careers.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                            </span>
                            Careers
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Organization</span>
                        <a href="{{ route('admin.board.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.board.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </span>
                            Board
                        </a>
                        <a href="{{ route('admin.portfolio.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            </span>
                            Portfolio
                        </a>
                    </div>

                    <div class="admin-nav-group">
                        <span class="admin-nav-label">Library</span>
                        <a href="{{ route('admin.media.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                            <span class="admin-nav-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </span>
                            Uploaded Files
                        </a>
                    </div>

                    @if (auth()->user()->isPrimaryAdmin())
                        <div class="admin-nav-group">
                            <span class="admin-nav-label">CMS Manager</span>
                            <a href="{{ route('admin.users.index') }}"
                               class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <span class="admin-nav-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v7m-6-4 6-6 6 6"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </span>
                                Admin Users
                            </a>
                        </div>
                    @endif
                </nav>

                <div class="admin-sidebar-footer">
                    <button type="button"
                            class="admin-theme-toggle"
                            data-admin-theme-toggle
                            aria-pressed="false"
                            aria-label="Switch to dark mode">
                        <span class="admin-theme-toggle-label">Appearance</span>
                        <span class="admin-theme-toggle-icons" aria-hidden="true">
                            <svg data-theme-icon="light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                            <svg data-theme-icon="dark" hidden viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                        </span>
                    </button>

                    <div class="admin-user-pill">
                        <span class="admin-user-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}</span>
                        <div>
                            <strong>{{ auth()->user()->name ?: 'CMS Admin' }}</strong>
                            <span>{{ auth()->user()->email }}</span>
                        </div>
                    </div>

                    <a href="{{ url('/?preview=1') }}" target="_blank" rel="noopener" class="admin-view-site">
                        View Website
                    </a>

                    <a href="{{ route('admin.password.change') }}" class="admin-view-site">
                        Change password
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="admin-logout">
                            Sign out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="admin-main">
                <header class="admin-topbar">
                    <div class="admin-topbar-start">
                        <button type="button" class="admin-nav-toggle" data-admin-nav-toggle aria-label="Open navigation menu" aria-expanded="false">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                        </button>

                        <div class="admin-topbar-intro">
                            <p class="admin-topbar-eyebrow">THLIN CMS</p>
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
                    </div>

                    <div class="admin-topbar-actions">
                        <a href="{{ route('admin.pages.create') }}" class="btn btn-light admin-topbar-btn">New page</a>
                        <a href="{{ url('/?preview=1') }}" target="_blank" rel="noopener" class="btn btn-primary admin-topbar-action">
                            View Website
                        </a>
                    </div>
                </header>

                <main class="admin-content">
                    @if (session('success') || session('status'))
                        <div class="admin-alert admin-alert-success">
                            {{ session('success') ?? session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="admin-alert admin-alert-error">
                            {{ session('error') }}
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

    @auth
        <dialog class="admin-help-dialog" id="admin-inline-help-dialog" aria-labelledby="admin-inline-help-title">
            <div class="admin-help-dialog-inner">
                <div class="admin-help-dialog-head">
                    <h2 id="admin-inline-help-title">How inline editing works</h2>
                    <button type="button" class="admin-help-dialog-close" data-admin-inline-help-close aria-label="Close help dialog">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                @include('partials.admin-inline-editing-help')
                <div class="admin-help-dialog-actions">
                    <button type="button" class="btn btn-light" data-admin-inline-help-dismiss>Remind me later</button>
                    <button type="button" class="btn btn-primary" data-admin-inline-help-open>Open Website Editor</button>
                </div>
            </div>
        </dialog>
    @endauth

    <script src="{{ asset('js/admin-shell.js') }}" defer></script>
    <script src="{{ asset('js/admin-media.js') }}" defer></script>
    <script src="{{ asset('js/admin-theme.js') }}" defer></script>
    <script src="{{ asset('js/admin-inline-help.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
