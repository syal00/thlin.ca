<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('title', $thlin['name'])</title>
    <meta name="description" content="@yield('meta_description', $thlin['tagline'])">

    {{-- Inter — primary typeface per the design system. font-display:swap
         avoids invisible-text flash; system-font fallback in tokens.css
         covers the case where this request fails/is blocked. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">

    {{-- Frontend rebuild — single clean cascade, no legacy files, no
         !important. Modular by concern per the design-system spec. --}}
    @php
        $thlinStylesheets = [
            'tokens', 'base', 'layout', 'header', 'navigation', 'footer',
            'buttons', 'forms', 'cards', 'hero', 'accessibility',
            'animations', 'utilities', 'pages', 'home',
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
@endphp
<body @class([
    'has-inline-edit-bar' => auth()->check(),
    'is-home-page' => request()->routeIs('home'),
    'is-custom-page' => $isCustomPageRoute,
    'has-page-hero' => ! request()->routeIs('home') && ! $isCustomPageRoute,
])>

    <div class="site-wrapper t-site">
    @auth
        @include('partials.admin-edit-bar')
    @endauth

    <a href="#main-content" class="skip-link t-skip-link">Skip to main content</a>

    @include('partials.header')

    <main id="main-content" class="t-main">
        @yield('content')
    </main>

    @include('partials.footer')

    <div class="t-a11y-toolbar" aria-label="Accessibility tools">
        <strong>Accessibility</strong>

        <button type="button" id="decreaseText" aria-label="Decrease text size">A-</button>
        <button type="button" id="increaseText" aria-label="Increase text size">A+</button>
        <button type="button" id="toggleContrast" aria-label="Toggle high contrast mode">High Contrast</button>
        <button type="button" id="resetAccessibility" aria-label="Reset accessibility settings">Reset</button>
    </div>

    <button type="button" id="backToTop" class="t-back-to-top" aria-label="Back to top">
        ↑
    </button>

    <div class="ai-help-widget" id="aiHelpWidget">
        <button type="button" class="ai-help-toggle" id="aiHelpToggle" aria-label="Open help options">
            AI Help
        </button>

        <div class="ai-help-panel" id="aiHelpPanel" aria-hidden="true">
            <div class="ai-help-header">
                <div>
                    <span>THLIN Support</span>
                    <h2>AI Help Assistant</h2>
                </div>

                <button type="button" id="aiHelpClose" aria-label="Close help widget">×</button>
            </div>

            <p class="ai-help-intro">
                Ask about THLIN services, portals, resources, and contact options.
            </p>

            <p class="ai-help-disclaimer">
                This assistant helps with website navigation only and does not provide medical advice.
            </p>

            <div class="ai-chat-box" id="aiChatBox" aria-live="polite">
                <div class="ai-message ai-message-bot">
                    Hi, I can help you find THLIN services and resources. What are you looking for?
                </div>
            </div>

            <div class="ai-quick-actions">
                <button type="button" data-question="Find health services">Find health services</button>
                <button type="button" data-question="Patient Portals">Patient Portals</button>
                <button type="button" data-question="Provider Portals">Provider Portals</button>
                <button type="button" data-question="Support & Training">Support &amp; Training</button>
                <button type="button" data-question="Contact THLIN">Contact THLIN</button>
            </div>

            <form class="ai-help-form" id="aiHelpForm">
                <label class="sr-only" for="aiHelpInput">Ask a question</label>
                <input type="text" id="aiHelpInput" placeholder="Ask a question" autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
    </div>

    <script src="{{ asset('js/ai-help-widget.js') }}" defer></script>
    <script src="{{ asset('js/accessibility.js') }}" defer></script>
    <script src="{{ asset('js/thlin.js') }}" defer></script>
    
    @auth
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
    @endauth
    @stack('scripts')
</body>
</html>