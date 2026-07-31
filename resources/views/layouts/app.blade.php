<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">

    <link rel="icon" href="{{ asset('favicon.png') }}?v={{ @filemtime(public_path('favicon.png')) ?: '1' }}" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v={{ @filemtime(public_path('apple-touch-icon.png')) ?: '1' }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">

    @php
        $thlinStylesheets = [
            'tokens', 'base', 'layout', 'header', 'navigation', 'footer',
            'buttons', 'forms', 'cards', 'hero', 'accessibility',
            'animations', 'utilities', 'pages', 'home', 'media',
        ];
    @endphp
    @foreach ($thlinStylesheets as $sheet)
        <link rel="stylesheet" href="{{ asset("css/{$sheet}.css") }}?v={{ @filemtime(public_path("css/{$sheet}.css")) ?: '1' }}">
    @endforeach

    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endauth
    @stack('head')
</head>
@php
    $builtInSections = ['products', 'partners', 'about'];
    $isCustomPageRoute = request()->routeIs('custom-pages.show')
        || (request()->routeIs('custom-pages.child.show') && ! in_array(request()->segment(1), $builtInSections, true));
    $publicPreview = session('public_preview', false) || request()->boolean('preview');
@endphp
<body @class([
    'has-inline-edit-bar' => auth()->check() && ! $publicPreview,
    'is-home-page' => request()->routeIs('home'),
    'is-custom-page' => $isCustomPageRoute,
    'has-page-hero' => ! request()->routeIs('home'),
])>

    <div class="site-wrapper t-site">
    <a href="#main-content" class="skip-link t-skip-link">Skip to main content</a>

    @include('partials.header')

    @hasSection('hero')
        @yield('hero')
    @endif

    <main id="main-content" class="t-main">
        @yield('content')
    </main>

    @include('partials.footer')

    <button type="button" id="backToTop" class="t-back-to-top" aria-label="Back to top">
        ↑
    </button>

    @auth
        @unless ($publicPreview)
            @include('partials.admin-edit-bar')
        @endunless
    @endauth
    </div>

    <script src="{{ asset('js/thlin.js') }}" defer></script>

    @auth
        @unless ($publicPreview)
        <script>
            window.inlineEditRoutes = {
                update: @json(route('admin.inline-update')),
                uploadImage: @json(route('admin.inline-upload-image')),
            };
            window.inlineEditTinyMce = {
                baseUrl: @json(asset('vendor/tinymce')),
            };
        </script>
        <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}?v={{ @filemtime(public_path('vendor/tinymce/tinymce.min.js')) ?: '1' }}"></script>
        <script src="{{ asset('js/inline-edit.js') }}?v={{ @filemtime(public_path('js/inline-edit.js')) ?: '1' }}" defer></script>
        @endunless
    @endauth
    @stack('scripts')
</body>
</html>
