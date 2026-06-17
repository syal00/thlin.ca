@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    @if ($page->slug === 'patient-portals')
        @include('partials.page-header', [
            'page' => $page,
            'eyebrow' => 'Patient Portals',
        ])
    @section('content')
        @if ($page->section === 'products')
            <section class="service-detail-hero">
                <div class="container">
                    <nav class="service-breadcrumb" aria-label="Breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <span class="separator">/</span>
                        <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Products &amp; Services</a>
                        <span class="separator">/</span>
                        <span class="current">{{ $page->title }}</span>
                    </nav>

                    <div class="service-detail-grid">
                        <div class="service-hero-content">
                            <span class="service-label">Products &amp; Services</span>

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
                                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-soft">Explore Products &amp; Services</a>
                            </div>
                        </div>

                        <div class="service-hero-card">
                            <span class="service-card-icon">+</span>
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
                                <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a></li>
                                <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
                                <li><a href="{{ route('pages.show', ['section' => 'products']) }}">All Products</a></li>
                            </ul>
                        </aside>
                    </div>
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
