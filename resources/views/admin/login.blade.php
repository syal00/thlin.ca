@extends('admin.guest-layout')

@section('title', 'Login')

@section('content')
    <div class="admin-login-shell">
        <aside class="admin-login-brand" aria-hidden="true">
            <div class="admin-login-brand-inner">
                <a href="{{ route('home') }}" class="admin-login-logo">
                    <span class="admin-login-logo-mark">THL</span>
                    <span class="admin-login-logo-text">THLIN</span>
                </a>

                <p class="admin-login-tagline">{{ config('thlin.tagline') }}</p>

                <ul class="admin-login-features">
                    <li>Edit pages and navigation content</li>
                    <li>Manage news, careers, and portfolio</li>
                    <li>Upload files and media assets</li>
                </ul>
            </div>
        </aside>

        <main class="admin-login-main">
            <div class="admin-login-card">
                <a href="{{ route('home') }}" class="admin-login-mobile-logo" aria-label="THLIN home">
                    <span class="admin-login-logo-mark">THL</span>
                    <span class="admin-login-logo-text">THLIN</span>
                </a>

                <div class="admin-login-card-header">
                    <h1>CMS sign in</h1>
                    <p>Sign in to edit site content. No public registration.</p>
                </div>

                @if ($errors->any())
                    <div class="admin-login-alert admin-login-alert--error" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('admin.login') }}" class="admin-login-form">
                    @csrf

                    <div class="admin-login-field">
                        <label for="email">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>

                    <div class="admin-login-field">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <label class="admin-login-remember">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        <span>Remember me on this device</span>
                    </label>

                    <button type="submit" class="admin-login-submit">Sign in</button>
                </form>

                <p class="admin-login-footer">
                    <a href="{{ route('home') }}">&larr; Back to {{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'site' }}</a>
                </p>
            </div>
        </main>
    </div>
@endsection
