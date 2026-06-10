@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)
@section('body_class', 'index-page')

@section('content')
    <section id="hero" class="hero section">
        <div class="hero-slider swiper init-swiper">
            <script type="application/json" class="swiper-config">
                {
                    "loop": true,
                    "speed": 800,
                    "effect": "fade",
                    "fadeEffect": { "crossFade": true },
                    "autoplay": { "delay": 6000 },
                    "slidesPerView": 1
                }
            </script>
            <div class="swiper-wrapper">
                <div class="swiper-slide" style="background-image: url('{{ asset('assets/img/health/showcase-1.webp') }}');"></div>
                <div class="swiper-slide" style="background-image: url('{{ asset('assets/img/health/showcase-7.webp') }}');"></div>
                <div class="swiper-slide" style="background-image: url('{{ asset('assets/img/health/showcase-11.webp') }}');"></div>
            </div>
            <div class="slide-overlay"></div>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="150">
                    <div class="hero-content">
                        <span class="hero-badge">
                            <span class="badge-dot"></span>
                            Digital Health Non-Profit
                        </span>

                        <h1
                            class="hero-headline"
                            data-editable="true"
                            data-model="page"
                            data-id="{{ $page->id }}"
                            data-field="title"
                        >{{ $page->title }}</h1>

                        <p
                            class="hero-subtext"
                            data-editable="true"
                            data-model="page"
                            data-id="{{ $page->id }}"
                            data-field="excerpt"
                        >{{ $page->excerpt }}</p>

                        <form class="thlin-search-form" action="{{ route('search') }}" method="get" role="search">
                            <label for="search-query" class="visually-hidden">Search site</label>
                            <div class="input-group">
                                <input type="search" id="search-query" name="q" class="form-control" placeholder="Search the site" value="{{ request('q') }}">
                                <button type="submit" class="btn btn-solid">Search</button>
                            </div>
                        </form>

                        <div class="action-row">
                            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-solid">Explore Products</a>
                            <a href="{{ route('contact') }}" class="btn btn-outline">
                                <i class="bi bi-chat-dots"></i>
                                <span>Contact Us</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="250">
                    <div class="info-stack">
                        <div class="info-card emergency-card">
                            <div class="card-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div class="card-meta">
                                <span class="card-label">Contact Us</span>
                                <span class="card-value">{{ $thlin['contact_phone'] }}</span>
                            </div>
                            <a href="tel:{{ preg_replace('/\D/', '', $thlin['contact_phone']) }}" class="card-action" aria-label="Call now">
                                <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>

                        <div class="info-card stats-card">
                            @foreach (array_slice($thlin['stats'], 0, 2) as $stat)
                                <div class="stat-cell">
                                    <span class="stat-number">{{ $stat['value'] }}</span>
                                    <span class="stat-label">{{ $stat['label'] }}</span>
                                </div>
                                @if (! $loop->last)<div class="stat-divider"></div>@endif
                            @endforeach
                        </div>

                        <div class="info-card review-card">
                            <div class="review-header">
                                <div class="review-rating">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <span class="review-score">Since 2001</span>
                            </div>
                            <p class="review-quote">"Connecting patients, caregivers, and health system planners to the services and information they need."</p>
                            <div class="review-author">
                                <span class="author-avatar"><i class="bi bi-heart-pulse"></i></span>
                                <div class="author-info">
                                    <span class="author-name">{{ $thlin['name'] }}</span>
                                    <span class="author-role">Award-Winning Non-Profit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="metrics-bar" data-aos="fade-up" data-aos-delay="300">
                @foreach ($thlin['stats'] as $stat)
                    <div class="metric-item">
                        <div class="metric-icon"><i class="bi bi-check-circle"></i></div>
                        <div class="metric-text">
                            <span class="metric-number">{{ $stat['value'] }}</span>
                            <span class="metric-label">{{ $stat['label'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="home-about" class="home-about section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row align-items-center mb-5 gy-4">
                <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
                    <span class="eyebrow-badge">System Navigation Made Easy</span>
                    <h2>Connecting Ontarians to Health &amp; Community Services</h2>
                    <p>{{ $page->excerpt }}</p>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="row g-3">
                        <div class="col-12">
                            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="feature-card primary-card d-block text-decoration-none">
                                <h3>Products &amp; Services</h3>
                                <p>Websites, collaboration tools and information portals built for your needs.</p>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="feature-card compact d-block text-decoration-none">
                                <h4>Partners</h4>
                                <p>Health care, municipalities &amp; OHTs.</p>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="feature-card compact d-block text-decoration-none">
                                <h4>Portfolio</h4>
                                <p>Examples of our latest projects.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section light-background">
        <div class="container" data-aos="fade-up">
            <div class="section-title text-center">
                <h2>thehealthline.ca</h2>
                <p>An authoritative health service directory that makes navigating the health care system easier.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <p>With 47,000 detailed records for home, community, primary, acute and long-term care services, our online service directory is the most widely used system navigation tool in Ontario.</p>
                    <a class="btn btn-solid mt-3" href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container" data-aos="fade-up">
            <div class="section-title text-center">
                <h2>Building Tools to Support our Communities</h2>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <p>Mapping the mosaic of services available within your community and presenting the information effectively takes careful work. We can help — whether you're enhancing an existing community information tool or building patient-centred websites.</p>
                    <a class="btn btn-outline mt-3" href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section id="featured-services" class="featured-services section light-background">
            <div class="container" data-aos="fade-up">
                <div class="section-title text-center">
                    <h2>Featured Projects</h2>
                    <p>Check out some examples of our latest work.</p>
                </div>
                <div class="row gy-4">
                    @foreach ($featuredPortfolio as $item)
                        <div class="col-lg-4 col-md-6">
                            <article class="service-card h-100">
                                <div class="card-media">
                                    @if ($item->image)
                                        <img
                                            src="{{ asset('storage/'.$item->image) }}"
                                            alt="{{ $item->title }}"
                                            class="img-fluid"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >
                                    @else
                                        <div
                                            class="portfolio-image-placeholder"
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="image"
                                        >Click to add image</div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="card-head">
                                        <h3
                                            data-editable="true"
                                            data-model="portfolio"
                                            data-id="{{ $item->id }}"
                                            data-field="title"
                                        >{{ $item->title }}</h3>
                                    </div>
                                    <p
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $item->id }}"
                                        data-field="excerpt"
                                    >{{ $item->excerpt }}</p>
                                    <div class="card-foot">
                                        <a href="{{ $item->url }}" class="link-action" target="_blank" rel="noopener">View Project <i class="bi bi-arrow-right"></i></a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="call-to-action" class="call-to-action section dark-background">
        <div class="container text-center" data-aos="fade-up">
            <h2>Interested in Collaborating?</h2>
            <p>We work with partners to improve information systems and connect the people of Ontario to relevant health and community services.</p>
            <a href="mailto:{{ $thlin['contact_email'] }}" class="btn btn-solid">{{ $thlin['contact_email'] }}</a>
        </div>
    </section>
@endsection
