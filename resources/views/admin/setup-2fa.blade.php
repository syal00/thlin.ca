@extends('admin.guest-layout')

@section('title', 'Set up two-factor authentication')

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
                    <li>Scan the QR code with your authenticator app</li>
                    <li>Or enter the setup key manually</li>
                    <li>Confirm with a code to finish sign-in</li>
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
                    <h1>Set up authenticator</h1>
                    <p>Scan this QR code once with your authenticator app, then enter the 6-digit code. If you scanned an older QR code before, remove the old <strong>THLIN CMS</strong> entry in your app first.</p>
                </div>

                @if (session('status'))
                    <div class="admin-login-alert" role="status">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="admin-login-alert admin-login-alert--error" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="admin-2fa-qr" aria-hidden="true">
                    {!! $qrCodeSvg !!}
                </div>

                <p class="admin-2fa-secret">
                    <span class="admin-2fa-secret-label">Setup key</span>
                    <code>{{ $secret }}</code>
                </p>

                <form method="post" action="{{ route('admin.login.setup-2fa.submit') }}" class="admin-login-form">
                    @csrf

                    <div class="admin-login-field">
                        <label for="code">Authenticator code</label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            inputmode="numeric"
                            maxlength="6"
                            placeholder="123456"
                            required
                            autofocus
                            autocomplete="one-time-code"
                        >
                    </div>

                    <button type="submit" class="admin-login-submit">Confirm and sign in</button>
                </form>

                <form method="post" action="{{ route('admin.login.setup-2fa.reset') }}" class="admin-login-form" style="margin-top: 1rem;">
                    @csrf
                    <button type="submit" class="admin-login-submit admin-login-submit--secondary">Generate a new QR code</button>
                </form>

                <p class="admin-login-footer">
                    <a href="{{ route('admin.login.verify.cancel') }}">&larr; Back to sign in</a>
                </p>
            </div>
        </main>
    </div>
@endsection
