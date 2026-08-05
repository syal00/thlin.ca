@extends('admin.guest-layout')

@section('title', 'Forgot password')

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
                    <li>Reset access to the CMS</li>
                    <li>Secure email verification link</li>
                    <li>Choose a new strong password</li>
                </ul>
            </div>
        </aside>

        <main class="admin-login-main">
            <div class="admin-login-card">
                <div class="admin-login-card-header">
                    <h1>Forgot your password?</h1>
                    <p>Enter your CMS account email and we will send a reset link.</p>
                </div>

                @if (session('status'))
                    <div class="admin-login-alert admin-login-alert--success" role="status">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if (session('dev_reset_url'))
                    <div class="admin-login-alert admin-login-alert--success" role="status">
                        <p><strong>Local development:</strong> Email is not configured on this server, so no message was sent to your inbox. Use this reset link instead:</p>
                        <p class="admin-reset-dev-link"><a href="{{ session('dev_reset_url') }}">{{ session('dev_reset_url') }}</a></p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-login-alert admin-login-alert--error" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('admin.password.email') }}" class="admin-login-form">
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

                    <button type="submit" class="admin-login-submit">Send reset link</button>
                </form>

                <p class="admin-login-footer">
                    <a href="{{ route('admin.login') }}">&larr; Back to sign in</a>
                </p>
            </div>
        </main>
    </div>
@endsection
