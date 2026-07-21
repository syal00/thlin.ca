@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' - ' . $thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    @include('partials.hero-home', ['page' => $page])
@endsection

@section('content')
    @php
        $homeStats = collect($thlin['stats']);
    @endphp

    <section class="home-stats">
        <div class="t-container">
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

    <section class="home-section">
        <div class="t-container">
            <div class="home-quick-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="home-quick-card reveal-on-scroll">
                    <img src="{{ asset('images/home/icon-website.png') }}" alt="" width="56" height="56">
                    @include('partials.site-setting', ['key' => 'home_quick_card_1_title', 'default' => 'Products & Services', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_quick_card_1_text', 'default' => 'We build websites, collaboration tools and information portals that meet our clients\' needs.', 'tag' => 'p', 'type' => 'textarea'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="80ms">
                    <img src="{{ asset('images/home/icon-tools.png') }}" alt="" width="56" height="56">
                    @include('partials.site-setting', ['key' => 'home_quick_card_2_title', 'default' => 'Tools', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_quick_card_2_text', 'default' => 'We can work with health care professionals, social service providers, municipalities and OHTs.', 'tag' => 'p', 'type' => 'textarea'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="home-quick-card reveal-on-scroll" data-reveal-delay="160ms">
                    <img src="{{ asset('images/home/icon-documents.png') }}" alt="" width="56" height="56">
                    @include('partials.site-setting', ['key' => 'home_quick_card_3_title', 'default' => 'Portfolio', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_quick_card_3_text', 'default' => 'Check out some examples of our latest projects!', 'tag' => 'p', 'type' => 'textarea'])
                </a>
            </div>
        </div>
    </section>

    <section class="home-section home-section--healthline">
        <div class="t-container">
            <div class="home-quick-grid" style="grid-template-columns: minmax(0, 1fr) minmax(300px, 0.9fr); align-items: center; gap: var(--t-space-xl);">
                <div class="reveal-on-scroll">
                    @include('partials.site-setting', ['key' => 'home_healthline_title', 'default' => 'thehealthline.ca', 'tag' => 'h2', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_healthline_text', 'default' => 'An authoritative health service directory that makes navigating the health care system easier. With 47,000 detailed records for home, community, primary, acute and long-term care services, Our online service directory is the most widely used, online system navigation tool in Ontario.', 'tag' => 'p', 'type' => 'textarea'])
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="t-btn t-btn-light">Learn More</a>
                </div>
                <div class="reveal-on-scroll" data-reveal-delay="120ms">
                    <img src="{{ asset('images/home/laptop-image.png') }}" alt="thehealthline.ca directory on laptop" style="width:100%; max-width:480px; margin:0 auto; border-radius: var(--t-radius-md);">
                </div>
            </div>
        </div>
    </section>

    <section class="home-section home-section--alt">
        <div class="t-container">
            <div class="t-section-head reveal-on-scroll">
                <span class="t-eyebrow">Products &amp; Services</span>
                @include('partials.site-setting', ['key' => 'home_products_title', 'default' => 'Digital tools built for easier system navigation.', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_products_subtitle', 'default' => 'We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="home-product-grid">
                <article class="t-card reveal-on-scroll">
                    <div class="t-card-media">
                        <img src="{{ asset('images/services/digital-service-directories.jpg') }}" alt="Digital service directories">
                    </div>
                    <div class="t-card-body">
                        @include('partials.site-setting', ['key' => 'home_products_card_1_title', 'default' => 'Digital service directories', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_1_text', 'default' => 'Organized directories that help people find health, social, and community services faster.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="t-card reveal-on-scroll" data-reveal-delay="80ms">
                    <div class="t-card-media">
                        <img src="{{ asset('images/services/community-information-tools.jpg') }}" alt="Community information tools">
                    </div>
                    <div class="t-card-body">
                        @include('partials.site-setting', ['key' => 'home_products_card_2_title', 'default' => 'Community information tools', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_2_text', 'default' => 'Searchable online tools designed around real user needs and local community resources.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="t-card reveal-on-scroll" data-reveal-delay="160ms">
                    <div class="t-card-media">
                        <img src="{{ asset('images/services/website-portal-development.jpg') }}" alt="Website and portal development">
                    </div>
                    <div class="t-card-body">
                        @include('partials.site-setting', ['key' => 'home_products_card_3_title', 'default' => 'Website and portal development', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_3_text', 'default' => 'Professional websites and portals that support content management and partner communication.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>

                <article class="t-card reveal-on-scroll" data-reveal-delay="240ms">
                    <div class="t-card-media">
                        <img src="{{ asset('images/services/data-content-support.jpg') }}" alt="Data and content support">
                    </div>
                    <div class="t-card-body">
                        @include('partials.site-setting', ['key' => 'home_products_card_4_title', 'default' => 'Data and content support', 'tag' => 'h3', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'home_products_card_4_text', 'default' => 'Support for keeping information accurate, structured, searchable, and useful for users.', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="home-section home-section--dark">
        <div class="t-container">
            <div class="home-portfolio-lead reveal-on-scroll">
                @include('partials.site-setting', ['key' => 'home_portfolio_title', 'default' => 'Building Tools to Support our Communities', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_portfolio_subtitle', 'default' => 'Mapping the mosaic of services available within your community and presenting the information effectively, takes careful work. We can help. Whether you\'re enhancing an existing community information tool or building patient-centred websites, featuring tools to help find condition-specific information, our tailored sites are built to meet user needs. Simple, easy to use and information-rich.', 'tag' => 'p', 'type' => 'textarea'])
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="t-btn t-btn-light">Learn More</a>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="home-section home-section--alt">
            <div class="t-container">
                <div class="t-card-grid">
                    @foreach ($featuredPortfolio as $item)
                        @include('partials.portfolio-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @include('partials.cta-section')

    @include('partials.page-updated', ['page' => $page])
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-network.js') }}?v={{ @filemtime(public_path('js/hero-network.js')) ?: '1' }}"
        defer></script>
@endpush
