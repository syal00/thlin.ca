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
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Products &amp; Services</span>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>

            <span class="section-kicker">Products &amp; Services</span>

            <h1
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="title"
            >{{ $page->title }}</h1>

            @if ($page->excerpt)
                <p
                    class="service-detail-excerpt"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="excerpt"
                >{{ $page->excerpt }}</p>
            @endif

            <div class="service-detail-actions">
                <a href="{{ route('contact') }}" class="service-btn service-btn-light">Contact Us</a>
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="service-btn service-btn-outline">
                    Explore Services
                </a>
            </div>
        </div>

        <aside class="service-hero-card">
            <h2>Service Highlights</h2>
            <ul class="service-highlight-list">
                <li>Trusted health and community service directory</li>
                <li>Supports patients, caregivers, and providers</li>
                <li>Helps users navigate health and community services</li>
            </ul>
        </aside>
    </div>
</section>

<section class="service-detail-content">
    <div class="container service-content-grid">
        <article class="service-main-card service-pro-card">
            <div
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="body"
                class="service-rich-content"
            >
                {!! $page->body !!}
            </div>
        </article>

        <aside class="service-side-card service-pro-sidebar">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Portals</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
            </ul>
        </aside>
    </div>
</section>

@includeIf('partials.page-cta')

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