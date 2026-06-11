<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}">
    
    <!-- Video Background Styles -->
    <style>
        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -2;
            object-fit: cover;
        }
        
        /* Optional overlay to darken video slightly for better text readability */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
        
        /* Make content appear above video */
        .skip-link, header, main, footer {
            position: relative;
            z-index: 1;
        }
        
        /* Optional: Add semi-transparent background to content areas for readability */
        main {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 8px;
            margin: 20px;
        }
    </style>
    
    @auth
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endauth
    @stack('head')
</head>
<body @auth class="has-inline-edit-bar" @endauth>
    
    <!-- Video Background -->
    <video autoplay muted loop id="video-background" poster="{{ asset('images/poster.jpg') }}">
        <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
        <!-- Add fallback formats if needed -->
        <source src="{{ asset('videos/background.webm') }}" type="video/webm">
        Your browser does not support the video tag.
    </video>
    
    <!-- Optional overlay for better text contrast -->
    <div class="video-overlay"></div>

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