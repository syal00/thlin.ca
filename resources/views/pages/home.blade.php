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
                <div class="hero-content home-hero-enter" data-animate="fade-up">
                    <span class="section-kicker">Guided Service Finder</span>

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
                </div>

                @include('partials.hero-side-card', ['searchInputId' => 'hero-search-input'])
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="home-section home-quick-cards-section">
        <div class="section-container">
            <div class="home-quick-cards-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="home-quick-card reveal-on-scroll" data-animate="fade-up">
                    <img src="{{ asset('images/home/icon-website.png') }}" alt="" aria-hidden="true" class="home-quick-card__img" width="64" height="64">
                    <div class="home-quick-card__body">
                        @include('partials.site-setting', ['key' => 'home_quick_card_1_title', 'default' => 'Products & Services', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_quick_card_1_text', 'default' => 'We build websites, collaboration tools and information portals that meet our clients\' needs.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="80ms" data-animate="fade-up">
                    <img src="{{ asset('images/home/icon-tools.png') }}" alt="" aria-hidden="true" class="home-quick-card__img" width="64" height="64">
                    <div class="home-quick-card__body">
                        @include('partials.site-setting', ['key' => 'home_quick_card_2_title', 'default' => 'Tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_quick_card_2_text', 'default' => 'We can work with health care professionals, social service providers, municipalities and OHTs.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="160ms" data-animate="fade-up">
                    <img src="{{ asset('images/home/icon-documents.png') }}" alt="" aria-hidden="true" class="home-quick-card__img" width="64" height="64">
                    <div class="home-quick-card__body">
                        @include('partials.site-setting', ['key' => 'home_quick_card_3_title', 'default' => 'Portfolio', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_quick_card_3_text', 'default' => 'Check out some examples of our latest projects!', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="home-section home-healthline-section">
        <div class="section-container home-healthline-grid">
            <div class="reveal-on-scroll" data-animate="fade-up">
                @include('partials.site-setting', ['key' => 'home_healthline_title', 'default' => 'thehealthline.ca', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_healthline_text', 'default' => 'An authoritative health service directory that makes navigating the health care system easier. With 47,000 detailed records for home, community, primary, acute and long-term care services, Our online service directory is the most widely used, online system navigation tool in Ontario.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-primary btn-lg learn-more-btn">Learn More</a>
            </div>
            <div class="home-healthline-visual reveal-on-scroll" data-reveal-delay="120ms" data-animate="fade-up">
                <img src="{{ asset('images/healthcare-digital-support.jpg') }}" alt="Older adults using digital health resources together" class="home-healthline-image">
            </div>
        </div>
    </section>

    <section class="home-stats-section" aria-label="THLIN impact statistics">
        <div class="container">
            <div class="home-stats-card" data-animate="fade-up">
                <div class="home-stat-item" data-animate="fade-up">
                    <span class="home-stat-icon">01</span>
                    <strong class="count-up" data-count="48071">0</strong>
                    <p>Service Records</p>
                </div>

                <div class="home-stat-item" data-animate="fade-up">
                    <span class="home-stat-icon">02</span>
                    <strong class="count-up" data-count="27773">0</strong>
                    <p>Agency Profiles</p>
                </div>

                <div class="home-stat-item" data-animate="fade-up">
                    <span class="home-stat-icon">03</span>
                    <strong class="count-up" data-count="31">0</strong>
                    <p>Specialty Websites</p>
                </div>

                <div class="home-stat-item" data-animate="fade-up">
                    <span class="home-stat-icon">04</span>
                    <strong class="count-up" data-count="1">0</strong>
                    <p>Provincial Database</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-products-section section-alt">
        <div class="section-container">
            <div class="section-heading reveal-on-scroll" data-animate="fade-up">
                <span class="section-kicker blue">Products &amp; Services</span>
                @include('partials.site-setting', ['key' => 'home_products_title', 'default' => 'Digital tools built for easier system navigation.', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_products_subtitle', 'default' => 'We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="service-grid home-products-grid">
                <article class="service-card home-product-card reveal-on-scroll" data-animate="fade-up">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/doctor-tablet-support.jpg') }}"
                            alt="Doctor and patient reviewing digital service information" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_1_title', 'default' => 'Digital service directories', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_1_text', 'default' => 'Organized directories that help people find health, social, and community services faster.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="80ms" data-animate="fade-up">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/home-community-tools.jpg') }}"
                            alt="Patient using a tablet with healthcare support" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_2_title', 'default' => 'Community information tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_2_text', 'default' => 'Searchable online tools designed around real user needs and local community resources.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="160ms" data-animate="fade-up">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/website-portal-development.jpg') }}"
                            alt="Website and portal development" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_3_title', 'default' => 'Website and portal development', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_3_text', 'default' => 'Professional websites and portals that support content management and partner communication.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="240ms" data-animate="fade-up">
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

    <section class="home-section home-portfolio-intro section-dark">
        <div class="section-container">
            <div class="reveal-on-scroll" data-animate="fade-up">
                @include('partials.site-setting', ['key' => 'home_portfolio_title', 'default' => 'Building Tools to Support our Communities', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_portfolio_subtitle', 'default' => 'Mapping the mosaic of services available within your community and presenting the information effectively, takes careful work. We can help. Whether you\'re enhancing an existing community information tool or building patient-centred websites, featuring tools to help find condition-specific information, our tailored sites are built to meet user needs. Simple, easy to use and information-rich.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="btn btn-light learn-more-btn">Learn More</a>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
            <section class="home-section home-portfolio-cards home-projects-section section-alt portfolio-section projects-section" data-animate="fade-up">
            <div class="section-container">
                <div class="home-projects-header portfolio-section-header projects-section-header" data-animate="fade-up">
                    <span class="section-kicker">Projects</span>
                    <h2>Examples of THLIN digital information work</h2>
                    <p>
                        Explore selected projects that show how THLIN supports health, community,
                        and partner information needs.
                    </p>
                </div>

                <div class="portfolio-grid home-projects-grid projects-grid featured-layout">
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
