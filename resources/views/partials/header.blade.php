<a href="#main-content" class="skip-link">Skip to main content</a>

<header class="site-header {{ request()->routeIs('home') ? 'is-home-header' : 'is-inner-header' }}">
    <div class="nav-wrapper">
        <div class="container">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="site-logo" aria-label="THLIN home">
                    <span class="logo-icon">THL</span>
                    <span class="logo-text">THLIN</span>
                </a>

                <nav class="site-nav" aria-label="Main navigation">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>

                        <li>
                            <a href="#">Products &amp; Services</a>
                            <ul>
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Portals</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}">Portfolio</a></li>
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
                                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">About Us</a>
                                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}">Annual Reports</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board-of-directors']) }}">Board of Directors</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">Careers</a></li>
                            </ul>
                        </li>

                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </nav>

                <a href="{{ route('contact') }}" class="nav-cta">Contact Us</a>
            </div>
        </div>
    </div>

    @hasSection('hero')
        @yield('hero')
    @endif
</header>