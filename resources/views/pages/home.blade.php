@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    <section class="home-hero">
        <div class="hero-video-bg" aria-hidden="true">
            <canvas class="hero-network-canvas" data-hero-network aria-hidden="true"></canvas>
            <video autoplay muted loop playsinline preload="auto" poster="{{ asset('images/hero-doctors.jpg') }}">
                <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
            </video>
            <div class="hero-video-overlay"></div>
        </div>

        <div class="hero-particles" aria-hidden="true">
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
            <span class="hero-particle"></span>
        </div>

        <div class="hero-inner">
            <div class="hero-grid">
                <div class="hero-content home-hero-enter">
                    <span class="section-kicker">THLIN</span>

                    <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'title', 'type' => 'text'])>{{ $page->title }}</h1>

                    <p
                        class="hero-lead"
                        @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'textarea'])
                    >{{ $page->excerpt }}</p>

                    <div class="hero-actions">
                        <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-light">
                            Explore Products &amp; Services
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light">
                            Contact Us
                        </a>
                    </div>

                    <div class="hero-badges hero-tags">
                        <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Focused</a>
                        <span>Community Health Support</span>
                        <span>Database &amp; Search Support</span>
                    </div>
                </div>

                @include('partials.hero-side-card', ['searchInputId' => 'hero-search-input'])
            </div>
        </div>
    </section>
@endsection

@section('content')
    @php
        $homeStats = collect($thlin['stats'])->sortByDesc(fn ($stat) => (int) str_replace(',', '', $stat['value']))->values();
    @endphp

    <section class="home-section home-section-help section-alt">
        <div class="section-container">
            <div class="stats-card reveal-on-scroll" aria-label="THLIN impact statistics">
                @foreach ($homeStats as $stat)
                    <div class="stat-item">
                        <span class="stat-number">{{ $stat['value'] }}</span>
                        <span class="stat-label">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="section-heading reveal-on-scroll">
                <span class="section-kicker blue">Quick Access</span>
                <h2>How can we help you?</h2>
                <p>Choose the path that best matches your needs and quickly access THLIN information, tools, and services.</p>
            </div>

            <div class="help-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="help-card reveal-on-scroll" data-reveal-delay="0ms">
                    <span>01</span>
                    <h3>Patients &amp; Families</h3>
                    <p>Find trusted health and community service information that is easier to understand and access.</p>
                    <strong>Find services</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="help-card reveal-on-scroll" data-reveal-delay="80ms">
                    <span>02</span>
                    <h3>Health &amp; Social Service Providers</h3>
                    <p>Connect people to programs, resources, and local service information.</p>
                    <strong>Support navigation</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'ontario-health-teams']) }}" class="help-card reveal-on-scroll" data-reveal-delay="160ms">
                    <span>03</span>
                    <h3>Partner Organizations</h3>
                    <p>Work with THLIN to build digital tools that support better access to information.</p>
                    <strong>Partner with us</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="help-card reveal-on-scroll" data-reveal-delay="240ms">
                    <span>04</span>
                    <h3>Community Members</h3>
                    <p>Explore online tools designed to make health and community information easier to find.</p>
                    <strong>Explore tools</strong>
                </a>
            </div>
        </div>
    </section>

    <section class="home-section section-light">
        <div class="section-container split-grid">
            <div class="reveal-on-scroll">
                <span class="section-kicker blue">About THLIN</span>
                <h2>Making health and community information easier to access.</h2>
                <p>
                    THLIN develops and supports digital information tools that help people navigate health and social services.
                    We work with partners to organize service information clearly, improve access, and support better system navigation.
                </p>
                <p>
                    Our work is focused on trusted information, usable online tools, and practical support for organizations serving communities across Ontario.
                </p>

                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}" class="btn btn-primary">
                    Learn About THLIN
                </a>
            </div>

            <div class="info-panel reveal-on-scroll" data-reveal-delay="120ms">
                <span>thehealthline.ca</span>
                <h3>Ontario’s health service directory</h3>
                <p>A trusted online directory helping people find home care, community support, health care, and social service resources.</p>

                <div class="info-list">
                    <div>
                        <strong>Service information</strong>
                        <small>Clear, organized, and searchable records.</small>
                    </div>
                    <div>
                        <strong>Navigation support</strong>
                        <small>Tools designed to help people find the right care.</small>
                    </div>
                    <div>
                        <strong>Partner focused</strong>
                        <small>Built with community and healthcare organizations.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section section-alt">
        <div class="section-container">
            <div class="section-heading reveal-on-scroll">
                <span class="section-kicker blue">Products &amp; Services</span>
                <h2>Digital tools built for easier system navigation.</h2>
                <p>We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.</p>
            </div>

            <div class="service-grid">
                <div class="service-card reveal-on-scroll">
                    <span></span>
                    <h3>Digital service directories</h3>
                    <p>Organized directories that help people find health, social, and community services faster.</p>
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    <h3>Community information tools</h3>
                    <p>Searchable online tools designed around real user needs and local community resources.</p>
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    <h3>Website and portal development</h3>
                    <p>Professional websites and portals that support content management and partner communication.</p>
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    <h3>Data and content support</h3>
                    <p>Support for keeping information accurate, structured, searchable, and useful for users.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="home-section section-light">
            <div class="section-container">
                <div class="section-heading reveal-on-scroll">
                    <span class="section-kicker blue">Who We Support</span>
                    <h2>Projects that support better access to information.</h2>
                    <p>Explore examples of THLIN’s digital work with healthcare and community partners.</p>
                </div>

                <div class="portfolio-grid">
                    @foreach ($featuredPortfolio as $item)
                        @include('partials.portfolio-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="home-cta-premium">
        <div class="section-container">
            <div class="cta-box reveal-on-scroll">
                <div>
                    <span class="section-kicker">Get Started</span>
                    <h2>Ready to connect with THLIN?</h2>
                    <p>Contact our team to learn more about our digital health information tools and partnership support.</p>
                </div>

                <div class="cta-actions">
                    <a href="{{ route('contact') }}" class="btn btn-light">Contact Us</a>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-outline-light">Explore Products &amp; Services</a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.page-updated', ['page' => $page])
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-network.js') }}?v={{ @filemtime(public_path('js/hero-network.js')) ?: '1' }}" defer></script>
@endpush
