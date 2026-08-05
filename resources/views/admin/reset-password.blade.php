@extends('admin.guest-layout')

@section('title', 'Reset password')

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
                    <li>Create a new CMS password</li>
                    <li>At least 12 characters required</li>
                    <li>Uppercase, lowercase, and a number</li>
                </ul>
            </div>
        </aside>

        <main class="admin-login-main">
            <div class="admin-login-card">
                <div class="admin-login-card-header">
                    <h1>Choose a new password</h1>
                    <p>Enter your new password below to finish resetting your account.</p>
                </div>

                @if ($errors->any())
                    <div class="admin-login-alert admin-login-alert--error" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="post" action="{{ route('admin.password.store') }}" class="admin-login-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="admin-login-field">
                        <label for="email">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $email) }}"
                            required
                            autofocus
                            autocomplete="username"
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

                    <button type="submit" class="admin-login-submit">Reset password</button>
                </form>

                <p class="admin-login-footer">
                    <a href="{{ route('admin.login') }}">&larr; Back to sign in</a>
                </p>
            </div>
        </main>
    </div>
@endsection
