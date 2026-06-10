<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="site-header">
    <div class="container">
        <div class="site-header__inner">
            <a href="{{ route('home') }}" class="site-logo" aria-label="THLIN home">
                <span class="site-logo__mark">THL</span>
                <span class="site-logo__text">thehealthline.ca Information Network</span>
            </a>

            <input type="checkbox" id="site-menu-toggle" class="site-menu-checkbox" aria-hidden="true">
            <label for="site-menu-toggle" class="site-menu-button">Menu</label>

            <nav class="site-nav" aria-label="Main navigation">
                <ul>
                    <li>
                        <a href="#">Products &amp; Services</a>
                        <ul>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Portals</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'information-management']) }}">Information Management</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}">Portfolio</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'resources']) }}">Resources</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">Partners</a>
                        <ul>
                            <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}">Health Care</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'municipalities']) }}">Municipalities</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'social-services']) }}">Social Services</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'oht']) }}">Ontario Health Teams</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="#">About</a>
                        <ul>
                            <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'about-us']) }}">About Us</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}">Annual Reports</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board-of-directors']) }}">Board of Directors</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">Careers</a></li>
                        </ul>
                    </li>

                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
        </div>
    </div>

    @hasSection('hero')
        @yield('hero')
    @endif
</header>
