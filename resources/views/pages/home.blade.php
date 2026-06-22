@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - ' . $thlin['name'])
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

                    <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_title', 'type' => 'text'])>{{ $page->hero_title ?: $page->title }}</h1>

                    <p class="hero-lead" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $page->excerpt !!}</p>

                    <div class="hero-actions">
                        <a href="{{ route('search') }}" class="btn btn-light hero-primary-btn">
                            Find Services
                        </a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-light">
                            Contact Us
                        </a>
                    </div>

                    <div class="hero-badges hero-tags">
                        <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient
                            Focused</a>
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
        $homeStats = collect($thlin['stats'])->sortByDesc(fn($stat) => (int) str_replace(',', '', $stat['value']))->values();
    @endphp

    <section class="home-section home-section-help home-help-section section-alt">
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
                @include('partials.site-setting', ['key' => 'home_quick_access_title', 'default' => 'How can we help you?', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_quick_access_subtitle', 'default' => 'Choose the path that best matches your needs and quickly access THLIN information, tools, and services.', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="help-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}"
                    class="help-card home-help-card reveal-on-scroll" data-reveal-delay="0ms">
                    <span class="help-card__number">01</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_1_title', 'default' => 'Patients & Families', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_1_text', 'default' => 'Find trusted health and community service information that is easier to understand and access.', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_1_link', 'default' => 'Find services', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}"
                    class="help-card home-help-card reveal-on-scroll" data-reveal-delay="80ms">
                    <span class="help-card__number">02</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_2_title', 'default' => 'Health & Social Service Providers', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_2_text', 'default' => 'Connect people to programs, resources, and local service information.', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_2_link', 'default' => 'Support navigation', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'ontario-health-teams']) }}"
                    class="help-card home-help-card reveal-on-scroll" data-reveal-delay="160ms">
                    <span class="help-card__number">03</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_3_title', 'default' => 'Partner Organizations', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_3_text', 'default' => 'Work with THLIN to build digital tools that support better access to information.', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_3_link', 'default' => 'Partner with us', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}"
                    class="help-card home-help-card reveal-on-scroll" data-reveal-delay="240ms">
                    <span class="help-card__number">04</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_4_title', 'default' => 'Community Members', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_4_text', 'default' => 'Explore online tools designed to make health and community information easier to find.', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_4_link', 'default' => 'Explore tools', 'tag' => 'strong', 'type' => 'text'])
                </a>
            </div>
        </div>
    </section>

    <section class="home-section section-light home-about-section about-section">
        <div class="section-container split-grid home-about-grid about-grid">
            <div class="reveal-on-scroll">
                <span class="section-kicker blue">About THLIN</span>
                @include('partials.site-setting', ['key' => 'home_about_title', 'default' => 'Making health and community information easier to access.', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_about_text_1', 'default' => 'THLIN develops and supports digital information tools that help people navigate health and social services. We work with partners to organize service information clearly, improve access, and support better system navigation.', 'tag' => 'p', 'type' => 'textarea'])
                @include('partials.site-setting', ['key' => 'home_about_text_2', 'default' => 'Our work is focused on trusted information, usable online tools, and practical support for organizations serving communities across Ontario.', 'tag' => 'p', 'type' => 'textarea'])

                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}" class="btn btn-primary">
                    @include('partials.site-setting', ['key' => 'home_about_button_label', 'default' => 'Learn About THLIN', 'tag' => 'span', 'type' => 'text'])
                </a>
            </div>

            <div class="info-panel reveal-on-scroll" data-reveal-delay="120ms">
                <div class="about-highlight-card">
                    @include('partials.site-setting', ['key' => 'home_about_panel_kicker', 'default' => 'thehealthline.ca', 'tag' => 'span', 'type' => 'text', 'class' => 'about-card-label'])

                    @include('partials.site-setting', ['key' => 'home_about_panel_title', 'default' => 'Ontario’s health service directory', 'tag' => 'h3', 'type' => 'text'])

                    @include('partials.site-setting', ['key' => 'home_about_panel_text', 'default' => 'A trusted online directory helping people find home care, community support, health care, and social service resources.', 'tag' => 'p', 'type' => 'textarea'])

                    <div class="about-highlight-list">
                        <div class="about-highlight-item">
                            <span class="about-highlight-icon">01</span>
                            <div>
                                <h4>Service Information</h4>
                                <p>Clear, organized, and searchable records.</p>
                            </div>
                        </div>

                        <div class="about-highlight-item">
                            <span class="about-highlight-icon">02</span>
                            <div>
                                <h4>Navigation Support</h4>
                                <p>Tools designed to help people find the right care.</p>
                            </div>
                        </div>

                        <div class="about-highlight-item">
                            <span class="about-highlight-icon">03</span>
                            <div>
                                <h4>Partner Focused</h4>
                                <p>Built with community and healthcare organizations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section section-alt home-products-section">
        <div class="section-container">
            <div class="section-heading reveal-on-scroll">
                <span class="section-kicker blue">Products &amp; Services</span>
                @include('partials.site-setting', ['key' => 'home_products_title', 'default' => 'Digital tools built for easier system navigation.', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_products_subtitle', 'default' => 'We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="service-grid">
                <article class="service-card home-product-card reveal-on-scroll">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/digital-service-directories.jpg') }}"
                            alt="Digital service directories" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_1_title', 'default' => 'Digital service directories', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_1_text', 'default' => 'Organized directories that help people find health, social, and community services faster.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/community-information-tools.jpg') }}"
                            alt="Community information tools" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_2_title', 'default' => 'Community information tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_2_text', 'default' => 'Searchable online tools designed around real user needs and local community resources.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/website-portal-development.jpg') }}"
                            alt="Website and portal development" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_3_title', 'default' => 'Website and portal development', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_3_text', 'default' => 'Professional websites and portals that support content management and partner communication.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/data-content-support.jpg') }}" alt="Data and content support"
                            class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_4_title', 'default' => 'Data and content support', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_4_text', 'default' => 'Support for keeping information accurate, structured, searchable, and useful for users.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="home-section section-light">
            <div class="section-container">
                <div class="section-heading reveal-on-scroll">
                    <span class="section-kicker blue">Who We Support</span>
                    @include('partials.site-setting', ['key' => 'home_portfolio_title', 'default' => 'Projects that support better access to information.', 'tag' => 'h2', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_portfolio_subtitle', 'default' => 'Explore examples of THLIN’s digital work with healthcare and community partners.', 'tag' => 'p', 'type' => 'textarea'])
                </div>

                <div class="portfolio-grid">
                    @foreach ($featuredPortfolio as $item)
                        @include('partials.portfolio-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.page-cta')

    @include('partials.page-updated', ['page' => $page])
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-network.js') }}?v={{ @filemtime(public_path('js/hero-network.js')) ?: '1' }}"
        defer></script>
@endpush