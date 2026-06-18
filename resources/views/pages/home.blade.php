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

                    <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_title', 'type' => 'text'])>{{ $page->hero_title ?: $page->title }}</h1>

                    <p
                        class="hero-lead"
                        @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'richtext'])
                    >{!! $page->excerpt !!}</p>

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
                @include('partials.site-setting', ['key' => 'home_quick_access_title', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_quick_access_subtitle', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="help-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="help-card reveal-on-scroll" data-reveal-delay="0ms">
                    <span class="help-card__number">01</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_1_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_1_text', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_1_link', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="help-card reveal-on-scroll" data-reveal-delay="80ms">
                    <span class="help-card__number">02</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_2_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_2_text', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_2_link', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'ontario-health-teams']) }}" class="help-card reveal-on-scroll" data-reveal-delay="160ms">
                    <span class="help-card__number">03</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_3_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_3_text', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_3_link', 'tag' => 'strong', 'type' => 'text'])
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="help-card reveal-on-scroll" data-reveal-delay="240ms">
                    <span class="help-card__number">04</span>
                    @include('partials.site-setting', ['key' => 'home_help_card_4_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_help_card_4_text', 'tag' => 'p', 'type' => 'textarea'])
                    @include('partials.site-setting', ['key' => 'home_help_card_4_link', 'tag' => 'strong', 'type' => 'text'])
                </a>
            </div>
        </div>
    </section>

    <section class="home-section section-light">
        <div class="section-container split-grid">
            <div class="reveal-on-scroll">
                <span class="section-kicker blue">About THLIN</span>
                @include('partials.site-setting', ['key' => 'home_about_title', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_about_text_1', 'tag' => 'p', 'type' => 'textarea'])
                @include('partials.site-setting', ['key' => 'home_about_text_2', 'tag' => 'p', 'type' => 'textarea'])

                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}" class="btn btn-primary">
                    @include('partials.site-setting', ['key' => 'home_about_button_label', 'tag' => 'span', 'type' => 'text'])
                </a>
            </div>

            <div class="info-panel reveal-on-scroll" data-reveal-delay="120ms">
                @include('partials.site-setting', ['key' => 'home_about_panel_kicker', 'tag' => 'span', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_about_panel_title', 'tag' => 'h3', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_about_panel_text', 'tag' => 'p', 'type' => 'textarea'])

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
                @include('partials.site-setting', ['key' => 'home_products_title', 'tag' => 'h2', 'type' => 'text'])
                @include('partials.site-setting', ['key' => 'home_products_subtitle', 'tag' => 'p', 'type' => 'textarea'])
            </div>

            <div class="service-grid">
                <div class="service-card reveal-on-scroll">
                    <span></span>
                    @include('partials.site-setting', ['key' => 'home_products_card_1_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_products_card_1_text', 'tag' => 'p', 'type' => 'textarea'])
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    @include('partials.site-setting', ['key' => 'home_products_card_2_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_products_card_2_text', 'tag' => 'p', 'type' => 'textarea'])
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    @include('partials.site-setting', ['key' => 'home_products_card_3_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_products_card_3_text', 'tag' => 'p', 'type' => 'textarea'])
                </div>

                <div class="service-card reveal-on-scroll">
                    <span></span>
                    @include('partials.site-setting', ['key' => 'home_products_card_4_title', 'tag' => 'h3', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_products_card_4_text', 'tag' => 'p', 'type' => 'textarea'])
                </div>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="home-section section-light">
            <div class="section-container">
                <div class="section-heading reveal-on-scroll">
                    <span class="section-kicker blue">Who We Support</span>
                    @include('partials.site-setting', ['key' => 'home_portfolio_title', 'tag' => 'h2', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_portfolio_subtitle', 'tag' => 'p', 'type' => 'textarea'])
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
                    @include('partials.site-setting', ['key' => 'cta_eyebrow', 'tag' => 'span', 'class' => 'section-kicker', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_cta_title', 'tag' => 'h2', 'type' => 'text'])
                    @include('partials.site-setting', ['key' => 'home_cta_text', 'tag' => 'p', 'type' => 'textarea'])
                </div>

                <div class="cta-actions">
                    <a href="{{ route('contact') }}" class="btn btn-light">@include('partials.site-setting', ['key' => 'cta_primary_label', 'tag' => 'span', 'type' => 'text'])</a>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-outline-light">@include('partials.site-setting', ['key' => 'cta_secondary_label', 'tag' => 'span', 'type' => 'text'])</a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.page-updated', ['page' => $page])
@endsection

@push('scripts')
    <script src="{{ asset('js/hero-network.js') }}?v={{ @filemtime(public_path('js/hero-network.js')) ?: '1' }}" defer></script>
@endpush
