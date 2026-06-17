@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    @if ($page->slug === 'patient-portals')
        @include('partials.page-header', [
            'page' => $page,
            'eyebrow' => 'Patient Portals',
        ])
    @else
        @php
            $sectionEyebrows = [
                'about' => 'About THLIN',
                'products' => 'Products & Services',
                'partners' => 'Partners',
            ];
            $eyebrow = $sectionEyebrows[$page->section] ?? 'THLIN';
        @endphp

        @include('partials.page-header', ['page' => $page, 'eyebrow' => $eyebrow])
    @endif
@endsection

@section('content')
    @if ($page->slug === 'patient-portals')
        <section class="home-section section-light">
            <div class="section-container">
                <div class="patient-content-grid">
                    <div class="content-shell patient-main-card">
                        <div class="cms-content">
                            @include('partials.cms-body', ['html' => $page->body])
                        </div>
                    </div>

                    <aside class="patient-side-card info-panel">
                        <h2>Quick Access</h2>
                        <ul>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a></li>
                            <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
                        </ul>
                    </aside>
                </div>
            </div>
        </section>

        <section class="home-section section-alt">
            <div class="section-container">
                <div class="section-heading">
                    <span class="section-kicker blue">Portal Features</span>
                    @elseif ($page->slug === 'healthline')
                        <!-- Service Detail Hero Section -->
                        <section class="service-detail-hero">
                            <div class="container">
                                <!-- Breadcrumb -->
                                <nav class="service-breadcrumb" aria-label="Breadcrumb">
                                    <a href="{{ route('home') }}">Home</a>
                                    <span class="separator">/</span>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'products']) }}">Products & Services</a>
                                    <span class="separator">/</span>
                                    <span class="current">{{ $page->title }}</span>
                                </nav>

                                <!-- Hero Content Grid -->
                                <div class="service-detail-grid">
                                    <div class="service-hero-content">
                                        <span class="service-label">Products & Services</span>

                                        <h1
                                            class="service-title"
                                            data-editable="true"
                                            data-model="page"
                                            data-id="{{ $page->id }}"
                                            data-field="title"
                                        >{{ $page->title }}</h1>

                                        <p
                                            class="service-excerpt"
                                            data-editable="true"
                                            data-model="page"
                                            data-id="{{ $page->id }}"
                                            data-field="excerpt"
                                        >{{ $page->excerpt }}</p>

                                        <div class="service-detail-actions">
                                            <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                                            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-soft">Explore Products</a>
                                        </div>
                                    </div>

                                    <div class="service-hero-card">
                                        <span class="service-card-icon">⭐</span>
                                        <h2>Product Highlights</h2>
                                        <p class="service-card-intro">Trusted health information and patient-centric solutions.</p>

                                        <ul class="service-highlight-list">
                                            <li>Comprehensive, trusted health information</li>
                                            <li>Easy-to-use patient interfaces</li>
                                            <li>24/7 accessible online services</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Main Content Section -->
                        <section class="service-detail-content">
                            <div class="container">
                                <div class="service-content-grid">
                                    <main class="service-main-card">
                                        <div
                                            data-editable="true"
                                            data-model="page"
                                            data-id="{{ $page->id }}"
                                            data-field="body"
                                        >
                                            {!! $page->body !!}
                                        </div>
                                    </main>

                                    <aside class="service-side-card">
                                        <h3>Quick Links</h3>
                                        <ul>
                                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Healthline Home</a></li>
                                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                                            <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support & Training</a></li>
                                            <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
                                            <li><a href="{{ route('pages.show', ['section' => 'products']) }}">All Products</a></li>
                                        </ul>
                                    </aside>
                                </div>
                            </div>
                        </section>
                    @else
                    <p>Patient portal solutions help people find information, connect with services, and navigate care more confidently.</p>
                </div>

                <div class="service-grid">
                    <div class="service-card">
                        <span></span>
                        <h3>Simple Access</h3>
                        <p>Clear interfaces that help patients and caregivers find the information they need quickly.</p>
                    </div>

                    <div class="service-card">
                        <span></span>
                        <h3>Trusted Information</h3>
                        <p>Content can be organized, reviewed, and maintained so users receive reliable guidance.</p>
                    </div>

                    <div class="service-card">
                        <span></span>
                        <h3>Partner Support</h3>
                        <p>Tools can support healthcare and community partners with better communication and navigation.</p>
                    </div>
                </div>
            </div>
        </section>

        @include('partials.page-cta')
    @else
        <section class="home-section section-light">
            <div class="section-container">
                <div class="content-shell">
                    <article class="cms-content">
                        @include('partials.cms-body', ['html' => $page->body])
                    </article>
                    @auth
                        <p class="cms-inline-edit-note">Full page content is edited in the CMS panel using <a href="{{ route('admin.pages.edit', $page) }}">Edit This Page in CMS</a>.</p>
                    @endauth
                </div>
            </div>
        </section>

        @include('partials.page-cta')
    @endif
@endsection
