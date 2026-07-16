{{-- Homepage hero. Markup contract with public/js/hero-network.js and
     public/js/thlin.js — do not rename .home-hero, [data-hero-network]/
     .hero-network-canvas, .hero-video-bg video, .hero-particle(s), or
     .hero-card without updating those scripts. --}}
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

    <div class="hero-inner t-container">
        <div class="hero-grid">
            <div class="hero-content home-hero-enter">
                <span class="t-eyebrow t-eyebrow--on-dark">THLIN</span>

                <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_title', 'type' => 'text'])>{{ $page->hero_title ?: $page->title }}</h1>

                <p class="hero-lead" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $page->excerpt !!}</p>

                <div class="hero-actions">
                    <a href="{{ route('search') }}" class="t-btn t-btn-light">
                        Find Services
                    </a>
                    <a href="{{ route('contact') }}" class="t-btn t-btn-outline">
                        Contact Us
                    </a>
                </div>
            </div>

            @include('partials.hero-side-card', ['searchInputId' => 'hero-search-input'])
        </div>
    </div>
</section>
