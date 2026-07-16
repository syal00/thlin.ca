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

        <div class="home-hero-container">
            <div class="home-hero-grid">
                <div class="home-hero-copy" data-animate="fade-up">
                    <span class="section-kicker">Guided Service Finder</span>

                    <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_title', 'type' => 'text'])>{{ $page->hero_title ?: $page->title }}</h1>

                    @php
                        $heroExcerpt = (string) ($page->excerpt ?? '');
                        $heroExcerpt = preg_replace('/<figure[^>]*>.*?<img[^>]*>.*?<\/figure>/is', '', $heroExcerpt) ?? $heroExcerpt;
                        $heroExcerpt = preg_replace('/<img[^>]*>/i', '', $heroExcerpt) ?? $heroExcerpt;
                    @endphp
                    <p @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $heroExcerpt !!}</p>

                    <div class="home-hero-actions">
                        <a href="{{ route('search') }}" class="hero-primary-btn">
                            Find Services
                        </a>
                        <a href="{{ route('contact') }}" class="hero-secondary-btn">
                            Contact Us
                        </a>
                    </div>
                </div>

                <div class="hero-service-card">
                    @include('partials.hero-side-card', ['searchInputId' => 'hero-search-input'])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="thlin-stats-section">
        <div class="thlin-stats-container">
            <div class="thlin-stats-grid">
                <article class="thlin-stat-card">
                    <span class="thlin-stat-label">01</span>
                    <strong class="count-up" data-count="48071">48,071</strong>
                    <p>Service Records</p>
                </article>

                <article class="thlin-stat-card">
                    <span class="thlin-stat-label">02</span>
                    <strong class="count-up" data-count="27773">27,773</strong>
                    <p>Agency Profiles</p>
                </article>

                <article class="thlin-stat-card">
                    <span class="thlin-stat-label">03</span>
                    <strong class="count-up" data-count="31">31</strong>
                    <p>Specialty Websites</p>
                </article>

                <article class="thlin-stat-card">
                    <span class="thlin-stat-label">04</span>
                    <strong class="count-up" data-count="1">1</strong>
                    <p>Provincial Database</p>
                </article>
            </div>
        </div>
    </section>

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

    @php
        $homePortfolioItems = $featuredPortfolio;
        if ($homePortfolioItems->isEmpty()) {
            $homePortfolioItems = \App\Models\PortfolioItem::query()->ordered()->limit(3)->get();
        }

        if ($homePortfolioItems->isEmpty()) {
            $homePortfolioItems = collect([
                (object) [
                    'id' => null,
                    'title' => 'AES Wellness Portal',
                    'excerpt' => 'A culturally-driven education and wellness information portal supporting access to community resources and services.',
                    'url' => 'https://aeswellnessportal.ca/',
                    'image_url' => null,
                ],
                (object) [
                    'id' => null,
                    'title' => 'FamilyInfo',
                    'excerpt' => 'Helping families access local programming, services, and community information in one organized online space.',
                    'url' => 'https://familyinfo.ca/',
                    'image_url' => null,
                ],
                (object) [
                    'id' => null,
                    'title' => 'Age-Friendly Sarnia Lambton',
                    'excerpt' => 'Supporting community members with accessible information, local resources, and age-friendly service navigation.',
                    'url' => 'https://agefriendlysarnialambton.ca/',
                    'image_url' => null,
                ],
            ]);
        }
    @endphp

    <section class="thlin-portfolio-section" data-animate="fade-up">
        <div class="thlin-portfolio-container">
            <div class="thlin-portfolio-header" data-animate="fade-up">
                <span class="section-kicker">Portfolio</span>
                <h2>Examples of THLIN digital information work</h2>
                <p>
                    Check out some examples of our latest projects and digital information tools
                    supporting healthcare and community service navigation.
                </p>
            </div>

            <div class="thlin-portfolio-grid">
                @foreach ($homePortfolioItems as $item)
                    @php
                        $itemId = $item->id ?? null;
                        $itemTitle = (string) ($item->title ?? 'Project');
                        $itemExcerpt = (string) ($item->excerpt ?? '');
                        $itemUrl = $item->url ?? null;
                        $itemImageUrl = $itemId && method_exists($item, 'imageUrl') ? $item->imageUrl() : ($item->image_url ?? null);
                    @endphp

                    <article class="thlin-portfolio-card">
                        <div class="thlin-portfolio-image">
                            @if ($itemImageUrl)
                                <img src="{{ $itemImageUrl }}" alt="{{ $itemTitle }} project preview"
                                    @if ($itemId)
                                        data-editable-image="true" data-model="portfolio" data-id="{{ $itemId }}"
                                        data-field="image"
                                    @endif
                                >
                            @else
                                <div class="thlin-portfolio-image-placeholder"
                                    @if ($itemId)
                                        data-editable-image="true" data-model="portfolio" data-id="{{ $itemId }}"
                                        data-field="image"
                                    @endif
                                >Project preview</div>
                            @endif
                        </div>

                        <div class="thlin-portfolio-content">
                            <span class="thlin-portfolio-tag">Project</span>
                            <h3 @if ($itemId) @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $itemId, 'field' => 'title', 'type' => 'text']) @endif>{{ $itemTitle }}</h3>
                            <p @if ($itemId) @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $itemId, 'field' => 'excerpt', 'type' => 'richtext']) @endif>{{ \Illuminate\Support\Str::limit(strip_tags($itemExcerpt), 160) }}</p>
                            @if ($itemUrl)
                                <a href="{{ $itemUrl }}" class="thlin-portfolio-link" target="_blank"
                                    rel="noopener">View project</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.page-cta')

    @include('partials.page-updated', ['page' => $page])
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-network.js') }}?v={{ @filemtime(public_path('js/hero-network.js')) ?: '1' }}"
        defer></script>
@endpush
