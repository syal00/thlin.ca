@extends('admin.guest-layout')

@section('title', ($required ?? true) ? 'Set your password' : 'Change password')

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
                    <li>Create and manage additional admin users</li>
                    <li>Edit pages, news, careers, and portfolio</li>
                    <li>Upload files and media assets</li>
                </ul>
            </div>
        </aside>

        <main class="admin-login-main">
            <div class="admin-login-card">
                <div class="admin-login-card-header">
                    @if ($required ?? true)
                        <h1>Set your password</h1>
                        <p>For security, choose a new password before using the CMS.</p>
                    @else
                        <h1>Change password</h1>
                        <p>Enter your current password, then choose a new one.</p>
                    @endif
                </div>

                @if (session('status'))
                    <div class="admin-login-alert admin-login-alert--success" role="status">
                        <p>{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-login-alert admin-login-alert--error" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('admin.password.update') }}" class="admin-login-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-login-field">
                        <label for="current_password">Current password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            required
                            autofocus
                            autocomplete="current-password"
                        >
                    </div>

                    <div class="admin-login-field">
                        <label for="password">New password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="admin-login-field">
                        <label for="password_confirmation">Confirm new password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <p class="form-helper" style="margin: 0 0 1rem;">Use at least 12 characters with uppercase, lowercase, and a number.</p>

                    <button type="submit" class="admin-login-submit">
                        {{ ($required ?? true) ? 'Save password and continue' : 'Update password' }}
                    </button>
                </form>

                <p class="admin-login-footer">
                    @if (! ($required ?? true))
                        <a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a>
                        <span aria-hidden="true"> · </span>
                    @endif
                    <form method="post" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="admin-login-link-button">Sign out</button>
                    </form>
                </p>
            </div>
        </main>
    </div>
@endsection
