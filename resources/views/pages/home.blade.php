@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    <section class="home-hero">
        <div class="container home-hero__grid">
            <div class="home-hero__content">
                <span class="eyebrow">THLIN</span>

                <h1
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="title"
                >{{ $page->title }}</h1>

                <p
                    class="home-hero__lead"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="excerpt"
                >{{ $page->excerpt }}</p>

                <div class="home-hero__actions">
                    <a class="btn btn-light" href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">
                        Explore thehealthline.ca
                    </a>
                    <a class="btn btn-outline-light" href="{{ route('contact') }}">
                        Contact Us
                    </a>
                </div>

                <div class="trust-pills" aria-label="THLIN highlights">
                    <span>Trusted health information</span>
                    <span>Community service navigation</span>
                    <span>Ontario care network support</span>
                </div>
            </div>

            <div class="home-hero__panel" aria-label="Service finder information">
                <h2>Find trusted health and community services</h2>
                <p>THLIN helps people, providers, and partners access reliable service information in one place.</p>

                <ul class="hero-checklist">
                    <li>Health and social service directories</li>
                    <li>Easy-to-use system navigation tools</li>
                    <li>Digital support for community partners</li>
                </ul>

                <form class="hero-search" action="{{ route('search') }}" method="get" role="search">
                    <label for="search-query" class="visually-hidden">Search the site</label>
                    <input
                        type="search"
                        id="search-query"
                        name="q"
                        placeholder="Search services, tools, news..."
                        value="{{ request('q') }}"
                    >
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="container">
            <div class="section-heading">
                <span class="eyebrow eyebrow-blue">Quick Access</span>
                <h2>How can we help you?</h2>
                <p>Choose the path that best matches your needs and quickly access THLIN information, tools, and services.</p>
            </div>

            <div class="access-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="access-card">
                    <span class="access-icon">01</span>
                    <h3>Patients &amp; Families</h3>
                    <p>Find trusted health and community service information that is easier to understand and access.</p>
                    <strong>Find services</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="access-card">
                    <span class="access-icon">02</span>
                    <h3>Health &amp; Social Service Providers</h3>
                    <p>Connect people to the right programs, resources, and local service information.</p>
                    <strong>Support navigation</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'oht']) }}" class="access-card">
                    <span class="access-icon">03</span>
                    <h3>Partner Organizations</h3>
                    <p>Work with THLIN to build digital tools that support better information access.</p>
                    <strong>Partner with us</strong>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="access-card">
                    <span class="access-icon">04</span>
                    <h3>Community Members</h3>
                    <p>Explore online tools designed to make health and community information easier to find.</p>
                    <strong>Explore tools</strong>
                </a>
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container about-grid">
            <div>
                <span class="eyebrow eyebrow-blue">About THLIN</span>
                <h2>Making health and community information easier to access.</h2>
                <p>
                    THLIN develops and supports digital information tools that help people navigate health and social services.
                    We work with partners to organize service information clearly, improve access, and support better system navigation.
                </p>
                <p>
                    Our work is focused on trusted information, usable online tools, and practical support for organizations serving communities across Ontario.
                </p>
                <a class="btn btn-primary" href="{{ route('pages.show', ['section' => 'about', 'page' => 'about-us']) }}">
                    Learn About THLIN
                </a>
            </div>

            <div class="info-card">
                <span class="info-card__label">thehealthline.ca</span>
                <h3>Ontario’s health service directory</h3>
                <p>
                    A trusted online directory helping people find home care, community support, health care, and social service resources.
                </p>

                <div class="info-list">
                    <div>
                        <strong>Service information</strong>
                        <span>Clear, organized, and searchable records.</span>
                    </div>
                    <div>
                        <strong>Navigation support</strong>
                        <span>Tools designed to help people find the right care.</span>
                    </div>
                    <div>
                        <strong>Partner focused</strong>
                        <span>Built with community and healthcare organizations.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-section section-light">
        <div class="container">
            <div class="section-heading">
                <span class="eyebrow eyebrow-blue">Impact</span>
                <h2>Trusted information with measurable reach.</h2>
                <p>THLIN supports digital access to health and community information through practical tools and partner collaboration.</p>
            </div>

            <div class="modern-stat-grid">
                @foreach ($thlin['stats'] as $stat)
                    <div class="modern-stat">
                        <strong>{{ $stat['value'] }}</strong>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="home-section">
        <div class="container">
            <div class="section-heading">
                <span class="eyebrow eyebrow-blue">Services</span>
                <h2>Digital tools built for easier system navigation.</h2>
                <p>We help organizations present information clearly and build online tools that are practical, accessible, and easy to maintain.</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <span></span>
                    <h3>Digital service directories</h3>
                    <p>Organized directories that help people find health, social, and community services faster.</p>
                </div>

                <div class="service-card">
                    <span></span>
                    <h3>Community information tools</h3>
                    <p>Searchable online tools designed around real user needs and local community resources.</p>
                </div>

                <div class="service-card">
                    <span></span>
                    <h3>Website and portal development</h3>
                    <p>Professional websites and portals that support content management and partner communication.</p>
                </div>

                <div class="service-card">
                    <span></span>
                    <h3>Data and content support</h3>
                    <p>Support for keeping information accurate, structured, searchable, and useful for users.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="home-section section-light">
            <div class="container">
                <div class="section-heading">
                    <span class="eyebrow eyebrow-blue">Featured Work</span>
                    <h2>Projects that support better access to information.</h2>
                    <p>Explore examples of THLIN’s digital work with healthcare and community partners.</p>
                </div>

                <div class="portfolio-grid-modern">
                    @foreach ($featuredPortfolio as $item)
                        <a href="{{ $item->url }}" class="portfolio-card-modern" target="_blank" rel="noopener">
                            @if ($item->image)
                                <img
                                    src="{{ asset('storage/'.$item->image) }}"
                                    alt="{{ $item->title }}"
                                    class="portfolio-card__image"
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

                            <div class="portfolio-card-modern__body">
                                <h3
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="title"
                                >{{ $item->title }}</h3>

                                <p
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="excerpt"
                                >{{ $item->excerpt }}</p>

                                <strong>View project</strong>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="home-cta">
        <div class="container home-cta__inner">
            <div>
                <span class="eyebrow">Work With THLIN</span>
                <h2>Let’s make health and community information easier to access.</h2>
                <p>
                    THLIN works with partners to improve digital access to trusted health and social service information.
                </p>
            </div>

            <div class="home-cta__actions">
                <a class="btn btn-light" href="{{ route('contact') }}">Contact Us</a>
                <a class="btn btn-outline-light" href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}">View Our Work</a>
            </div>
        </div>
    </section>
@endsection