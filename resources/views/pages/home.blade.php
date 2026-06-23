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
                </div>

                @include('partials.hero-side-card', ['searchInputId' => 'hero-search-input'])
            </div>
        </div>
    </section>
@endsection

@section('content')
    @php
        $homeStats = collect($thlin['stats']);
    @endphp

    <section class="home-section home-quick-cards-section">
        <div class="section-container">
            <div class="home-quick-cards-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="home-quick-card reveal-on-scroll">
                    <img src="{{ asset('images/home/icon-website.png') }}" alt="" class="home-quick-card__img" width="64" height="64">
                    <div class="home-quick-card__body">
                        @include('partials.site-setting', ['key' => 'home_quick_card_1_title', 'default' => 'Products & Services', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_quick_card_1_text', 'default' => 'We build websites, collaboration tools and information portals that meet our clients\' needs.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="80ms">
                    <img src="{{ asset('images/home/icon-tools.png') }}" alt="" class="home-quick-card__img" width="64" height="64">
                    <div class="home-quick-card__body">
                        @include('partials.site-setting', ['key' => 'home_quick_card_2_title', 'default' => 'Tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_quick_card_2_text', 'default' => 'We can work with health care professionals, social service providers, municipalities and OHTs.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="160ms">
                    <img src="{{ asset('images/home/icon-documents.png') }}" alt="" class="home-quick-card__img" width="64" height="64">
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
            <div class="reveal-on-scroll">
                @include('partials.site-setting', ['key' => 'home_healthline_title', 'default' => 'thehealthline.ca', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_healthline_text', 'default' => 'An authoritative health service directory that makes navigating the health care system easier. With 47,000 detailed records for home, community, primary, acute and long-term care services, Our online service directory is the most widely used, online system navigation tool in Ontario.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-primary btn-lg">Learn More</a>
            </div>
            <div class="home-healthline-visual reveal-on-scroll" data-reveal-delay="120ms">
                <img src="{{ asset('images/home/laptop-image.png') }}" alt="thehealthline.ca directory on laptop" class="home-healthline-image">
            </div>
        </div>
    </section>

    <section class="home-section home-stats-section">
        <div class="section-container">
            <div class="stats-card reveal-on-scroll" aria-label="THLIN impact statistics">
                @foreach ($homeStats as $stat)
                    <div class="stat-item">
                        <span class="stat-number">{{ $stat['value'] }}</span>
                        <span class="stat-label">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-section home-products-section section-alt">
        <div class="section-container">
            <div class="section-heading reveal-on-scroll">
                <span class="section-kicker blue">Products &amp; Services</span>
                @include('partials.site-setting', ['key' => 'home_products_title', 'default' => 'Digital tools built for easier system navigation.', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_products_subtitle', 'default' => 'We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="service-grid home-products-grid">
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

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="80ms">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/community-information-tools.jpg') }}"
                            alt="Community information tools" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_2_title', 'default' => 'Community information tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_2_text', 'default' => 'Searchable online tools designed around real user needs and local community resources.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="160ms">
                    <div class="service-card-image-wrapper">
                        <img src="{{ asset('images/services/website-portal-development.jpg') }}"
                            alt="Website and portal development" class="service-card-img">
                    </div>
                    <div class="service-card-content">
                        @include('partials.site-setting', ['key' => 'home_products_card_3_title', 'default' => 'Website and portal development', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_3_text', 'default' => 'Professional websites and portals that support content management and partner communication.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="service-card home-product-card reveal-on-scroll" data-reveal-delay="240ms">
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
            <div class="reveal-on-scroll">
                @include('partials.site-setting', ['key' => 'home_portfolio_title', 'default' => 'Building Tools to Support our Communities', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_portfolio_subtitle', 'default' => 'Mapping the mosaic of services available within your community and presenting the information effectively, takes careful work. We can help. Whether you\'re enhancing an existing community information tool or building patient-centred websites, featuring tools to help find condition-specific information, our tailored sites are built to meet user needs. Simple, easy to use and information-rich.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="btn btn-light">Learn More</a>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
            <section class="home-section home-portfolio-cards section-alt portfolio-section">
            <div class="section-container">
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
