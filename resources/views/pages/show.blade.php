@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('content')

@if ($page->section === 'about' && $page->slug === 'us')

<section class="about-hero">
    <div class="container about-hero-grid">
        <div>
            <div class="about-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>About</span>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>

            <span class="section-kicker">About THLIN</span>

            <h1
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="title"
            >{{ $page->title }}</h1>

            @if ($page->excerpt)
                <p
                    class="about-hero-text"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="excerpt"
                >{{ $page->excerpt }}</p>
            @endif

            <div class="about-actions">
                <a href="{{ route('contact') }}" class="about-btn about-btn-primary">Contact Us</a>
                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}" class="about-btn about-btn-secondary">Meet Our Board</a>
            </div>
        </div>

        <aside class="about-hero-card">
            <span class="about-card-label">Founded</span>
            <strong>2001</strong>
            <p>
                Supporting Ontario communities with trusted digital health and community service information.
            </p>

            <div class="about-mini-stats">
                <div>
                    <span>20+</span>
                    <p>Years of service</p>
                </div>
                <div>
                    <span>Ontario</span>
                    <p>Service focus</p>
                </div>
            </div>
        </aside>
    </div>
</section>

<section class="about-content-section">
    <div class="container about-content-grid">
        <article class="about-main-card">
            <span class="section-kicker blue">Our Story</span>

            <div
                class="about-rich-content"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="body"
            >
                {!! $page->body !!}
            </div>

            <a href="{{ route('contact') }}" class="about-story-btn">Contact Us</a>
        </article>

        <aside class="about-side-card">
            <h2>About THLIN</h2>
            <ul>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">Our Story</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}">Board</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}">Annual Reports</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">Careers</a></li>
            </ul>
        </aside>
    </div>
</section>

@includeIf('partials.page-cta')

@elseif (($section ?? $page->section) === 'products' || $page->section === 'products')

<section class="service-detail-hero">
    <div class="container service-detail-grid">
        <div class="service-detail-copy">
            <div class="service-breadcrumb">
                @if ($page->slug === 'healthline')
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Services</a>
                    <span>/</span>
                    <span>thehealthline.ca</span>
                @else
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <span>Products &amp; Services</span>
                    <span>/</span>
                    <span>{{ $page->title }}</span>
                @endif
            </div>

            <span class="section-kicker">Products &amp; Services</span>

            <h1
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="title"
            >{{ $page->slug === 'healthline' ? 'Trusted Health & Community Service Navigation' : ($page->title ?: 'Trusted Health & Community Service Navigation') }}</h1>

            <p
                class="service-detail-excerpt"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="excerpt"
            >{{ $page->slug === 'healthline' ? 'Helping patients, caregivers, providers, and community partners find reliable service information across Ontario.' : ($page->excerpt ?: 'Helping patients, caregivers, providers, and community partners find reliable service information across Ontario.') }}</p>

            <div class="service-detail-actions">
                <a href="{{ route('search') }}" class="service-btn service-btn-light">Find Services</a>
                <a href="{{ route('contact') }}" class="service-btn service-btn-outline">Contact THLIN</a>
            </div>

            <div class="service-hero-tags">
                <span>Verified Information</span>
                <span>Ontario-Wide Directory</span>
                <span>Patient &amp; Provider Support</span>
            </div>
        </div>

        <aside class="service-hero-card">
            <h2>How thehealthline.ca Helps</h2>
            <ul class="service-highlight-list">
                <li>
                    <strong>Verified Service Listings</strong>
                    <span>Regularly updated health and community service information.</span>
                </li>
                <li>
                    <strong>Easier System Navigation</strong>
                    <span>Helping users quickly find the right care and support.</span>
                </li>
                <li>
                    <strong>Built for Ontario Partners</strong>
                    <span>Supporting patients, caregivers, providers, and organizations.</span>
                </li>
            </ul>
        </aside>
    </div>
</section>

<section class="service-detail-content">
    <div class="container service-content-grid">
        <article class="service-main-card service-pro-card">
            <div
                class="service-rich-content"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="body"
            >
                {!! $page->body !!}
            </div>

            <a href="{{ route('search') }}" class="service-story-btn">Find Services</a>

            <section class="service-feature-cards-section" aria-label="Service Features">
                <div class="service-feature-heading">
                    <span class="section-kicker blue">Service Features</span>
                    <h2>Built to make service information easier to find and use</h2>
                    <p>
                        thehealthline.ca helps people, caregivers, providers, and community partners access trusted health and community service information across Ontario.
                    </p>
                </div>

                <div class="service-feature-cards">
                    <article class="service-feature-card">
                        <span class="feature-number">01</span>
                        <h3>Trusted Service Directory</h3>
                        <p>Search detailed health and community service listings across Ontario with clear, reliable information.</p>
                    </article>

                    <article class="service-feature-card">
                        <span class="feature-number">02</span>
                        <h3>Accurate Information</h3>
                        <p>Service details are reviewed, organized, and refreshed to help users find up-to-date information.</p>
                    </article>

                    <article class="service-feature-card">
                        <span class="feature-number">03</span>
                        <h3>Easy Navigation</h3>
                        <p>Designed to help patients, caregivers, and providers quickly find the right services and support.</p>
                    </article>

                    <article class="service-feature-card">
                        <span class="feature-number">04</span>
                        <h3>Connected Care Support</h3>
                        <p>Helps community partners and health system teams connect people to relevant local resources.</p>
                    </article>
                </div>
            </section>

            <section class="why-matters-section">
                <div class="why-matters-header">
                    <span class="section-kicker blue">Why It Matters</span>
                    <h2>Clear information helps people access the right support</h2>
                    <p>
                        Patients, providers, and communities need trusted service information to make better decisions and connect people with the right care.
                    </p>
                </div>

                <div class="why-matters-grid">
                    <article class="why-matters-card">
                        <span class="why-number">01</span>
                        <h3>Patients</h3>
                        <p>Patients need clear, simple information to understand available health and community services.</p>
                    </article>

                    <article class="why-matters-card">
                        <span class="why-number">02</span>
                        <h3>Providers</h3>
                        <p>Providers need accurate service data to guide referrals, care coordination, and system navigation.</p>
                    </article>

                    <article class="why-matters-card">
                        <span class="why-number">03</span>
                        <h3>Communities</h3>
                        <p>Communities need connected digital tools that make local support easier to find and access.</p>
                    </article>
                </div>

                <div class="why-matters-note">
                    <p>
                        THLIN brings these needs together through trusted digital tools, service directories, and partnership-focused solutions.
                    </p>
                </div>
            </section>
        </article>

        <aside class="service-side-card service-pro-sidebar">
            <span class="sidebar-label">Explore</span>
            <h2>Explore Services</h2>
            <ul>
                <li>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">
                        thehealthline.ca
                    </a>
                </li>

                <li>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">
                        Patient Portals
                    </a>
                </li>

                <li>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">
                        Provider Portals
                    </a>
                </li>

                <li>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">
                        Support &amp; Training
                    </a>
                </li>

                <li>
                    <a href="{{ route('contact') }}">
                        Contact THLIN
                    </a>
                </li>
            </ul>
        </aside>
    </div>
</section>

@include('partials.page-cta')

@else

<section class="page-content-section">
    <div class="container page-content-card">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>

        @if ($page->excerpt)
            <p
                class="page-excerpt"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="excerpt"
            >{{ $page->excerpt }}</p>
        @endif

        <div
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >
            {!! $page->body !!}
        </div>
    </div>
</section>

@includeIf('partials.page-cta')

@endif

@endsection