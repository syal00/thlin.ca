<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="footer-logo" aria-label="THLIN Home">
                <img src="{{ asset('images/thlin-logo.png') }}" alt="THLIN logo">
            </a>

            <p>
                THLIN helps people and organizations access trusted health and community
                service information through practical digital tools.
            </p>
        </div>

        <div class="footer-column">
            <h2>@include('partials.site-setting', ['key' => 'footer_quick_links_heading', 'default' => 'Quick Links', 'tag' => 'span'])</h2>
            <nav aria-label="Footer navigation">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">About</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                    @if (app()->environment('local'))
                        <li><a href="{{ route('admin.login') }}">CMS Login</a></li>
                    @endif
                </ul>
            </nav>
        </div>

        <div class="footer-column">
            <h2>@include('partials.site-setting', ['key' => 'footer_services_heading', 'default' => 'Services', 'tag' => 'span'])</h2>
            <ul>
                @foreach (config('thlin.navigation.products.items') as $slug => $label)
                    <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => $slug]) }}">{{ $label }}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="footer-column">
            <h2>@include('partials.site-setting', ['key' => 'footer_connect_heading', 'default' => 'Connect', 'tag' => 'span'])</h2>
            <ul>
                <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
                <li><a href="https://www.thehealthline.ca" target="_blank" rel="noopener">Visit thehealthline.ca</a></li>
                <li><a href="https://www.youtube.com" target="_blank" rel="noopener">YouTube</a></li>
                <li><a href="https://twitter.com" target="_blank" rel="noopener">Twitter</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; {{ date('Y') }} @include('partials.site-setting', ['key' => 'footer_copyright', 'default' => 'thehealthline.ca Information Network. All rights reserved.'])</p>
            <a href="{{ route('contact') }}">@include('partials.site-setting', ['key' => 'footer_cta_link_label', 'default' => 'Get in touch'])</a>
        </div>
    </div>
</footer>
