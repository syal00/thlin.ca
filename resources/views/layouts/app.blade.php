<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}">

    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endauth
    @stack('head')
</head>
<body @class([
    'has-inline-edit-bar' => auth()->check(),
    'page-home' => request()->routeIs('home'),
])>
    @if (request()->routeIs('home'))
        <video
            autoplay
            muted
            loop
            playsinline
            id="video-background"
            class="home-video-bg"
            aria-hidden="true"
            poster="{{ asset('images/poster.jpg') }}"
        >
            <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
            <source src="{{ asset('videos/background.webm') }}" type="video/webm">
        </video>
        <div class="home-video-overlay" aria-hidden="true"></div>
    @endif

    @auth
        @include('partials.admin-edit-bar')
    @endauth

    <a href="#main-content" class="skip-link">Skip to main content</a>

    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/thlin.js') }}" defer></script>
    
    @auth
        <script>
            window.inlineEditRoutes = {
                update: @json(route('admin.inline-update')),
                uploadImage: @json(route('admin.inline-upload-image')),
            };
        </script>
        <script src="{{ asset('js/inline-editing.js') }}" defer></script>
    @endauth
    @stack('scripts')
</body>
</html>