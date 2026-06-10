<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/thlin-overrides.css') }}" rel="stylesheet">

    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endauth
    @stack('head')
</head>
<body class="@yield('body_class', 'inner-page') @auth has-inline-edit-bar @endauth">
    @auth
        @include('partials.admin-edit-bar')
    @endauth

    <a href="#main-content" class="skip-link">Skip to main content</a>

    @include('partials.header')

    <main id="main-content" class="main">
        @yield('content')
    </main>

    @include('partials.footer')

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
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
