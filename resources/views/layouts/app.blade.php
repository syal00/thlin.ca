<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}">
    @stack('head')
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/thlin.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
