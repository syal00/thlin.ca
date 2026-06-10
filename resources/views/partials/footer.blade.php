<footer id="footer" class="footer dark-background">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                        <span class="sitename">{{ $thlin['name'] }}</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>{{ $thlin['tagline'] }}</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>{{ $thlin['contact_phone'] }}</span></p>
                        <p><strong>Email:</strong> <span>{{ $thlin['contact_email'] }}</span></p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Products</h4>
                    <ul>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthchat']) }}">healthchat.ca</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}">Portfolio</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>About</h4>
                    <ul>
                        <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">About Us</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">Careers</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}">Board</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Partners</h4>
                    <ul>
                        <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}">Health Care</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'municipalities']) }}">Municipalities</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'social-services']) }}">Social Services</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Connect</h4>
                    <ul>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ $thlin['social']['thehealthline'] }}" target="_blank" rel="noopener">thehealthline.ca</a></li>
                        <li><a href="{{ $thlin['social']['youtube'] }}" target="_blank" rel="noopener">YouTube</a></li>
                        <li><a href="{{ $thlin['social']['twitter'] }}" target="_blank" rel="noopener">Twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright text-center">
        <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">
            <div class="d-flex flex-column align-items-center align-items-lg-start">
                <div>&copy; {{ date('Y') }} <strong><span>{{ $thlin['name'] }}</span></strong>. All Rights Reserved</div>
            </div>
            <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
                <a href="{{ $thlin['social']['twitter'] }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="{{ $thlin['social']['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                <a href="{{ $thlin['social']['thehealthline'] }}" target="_blank" rel="noopener" aria-label="thehealthline.ca"><i class="bi bi-link-45deg"></i></a>
            </div>
        </div>
    </div>
</footer>
