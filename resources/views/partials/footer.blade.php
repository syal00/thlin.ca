<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="footer-logo">
                <span class="footer-logo-mark">THL</span>
                <span>thehealthline.ca</span>
            </a>

            <p>
                THLIN helps people and organizations access trusted health and community service information through practical digital tools.
            </p>
        </div>

        <div class="footer-column">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'about-us']) }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                @if (app()->environment('local'))
                    <li><a href="{{ route('admin.login') }}">CMS Login</a></li>
                @endif
            </ul>
        </div>

        <div class="footer-column">
            <h2>Services</h2>
            <ul>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Service Directories</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Portals</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}">Portfolio</a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h2>Connect</h2>
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
            <p>&copy; {{ date('Y') }} thehealthline.ca Information Network. All rights reserved.</p>
            <a href="{{ route('contact') }}">Get in touch</a>
        </div>
    </div>
</footer>