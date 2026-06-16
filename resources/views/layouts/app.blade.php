<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}?v={{ @filemtime(public_path('css/thlin.css')) ?: '1' }}">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v={{ @filemtime(public_path('css/site.css')) ?: '1' }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ @filemtime(public_path('css/custom.css')) ?: '1' }}">
    
    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endauth
    @stack('head')
</head>
<body @class([
    'has-inline-edit-bar' => auth()->check(),
    'is-home-page' => request()->routeIs('home'),
    'has-page-hero' => ! request()->routeIs('home'),
])>

    <div class="site-wrapper">
    @auth
        @include('partials.admin-edit-bar')
    @endauth

    <a href="#main-content" class="skip-link">Skip to main content</a>

    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')
    </div>

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