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
                        <div
                            class="cms-content"
                            data-editable="true"
                            data-model="page"
                            data-id="{{ $page->id }}"
                            data-field="body"
                        >
                            {!! $page->body !!}
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
                    <h2>Designed for easier access and better support.</h2>
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
                    <article
                        class="cms-content"
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="body"
                    >
                        {!! $page->body !!}
                    </article>
                </div>
            </div>
        </section>

        @include('partials.page-cta')
    @endif
@endsection
