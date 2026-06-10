@if ($page->slug === 'patient-portals')
    @extends('layouts.app')

    @section('title', $page->title.' - '.$thlin['name'])
    @section('meta_description', $page->excerpt)

    @section('content')
        <section class="patient-hero">
            <div class="container patient-hero-grid">
                <div>
                    <span class="section-kicker blue">Patient Portals</span>

                    <h1
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="title"
                    >{{ $page->title }}</h1>

                    <p
                        class="patient-lead"
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="excerpt"
                    >{{ $page->excerpt }}</p>

                    <div class="patient-actions">
                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                        <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="btn btn-soft">Explore thehealthline.ca</a>
                    </div>
                </div>

                <div class="patient-hero-card">
                    <span class="patient-card-icon">+</span>
                    <h2>Supporting patients and caregivers</h2>
                    <p>Patient-centred websites and tools designed to make care information easier to access, understand, and manage.</p>

                    <ul>
                        <li>Easy access to trusted information</li>
                        <li>Simple navigation for patients and families</li>
                        <li>Digital tools for communication and support</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="patient-section">
            <div class="container patient-content-grid">
                <div class="patient-main-card">
                    <div
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="body"
                    >
                        {!! $page->body !!}
                    </div>
                </div>

                <aside class="patient-side-card">
                    <h2>Quick Access</h2>
                    <ul>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a></li>
                        <li><a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a></li>
                        <li><a href="{{ route('contact') }}">Contact THLIN</a></li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="patient-feature-section">
            <div class="container">
                <div class="section-heading">
                    <span class="section-kicker blue">Portal Features</span>
                    <h2>Designed for easier access and better support.</h2>
                    <p>Patient portal solutions help people find information, connect with services, and navigate care more confidently.</p>
                </div>

                <div class="patient-feature-grid">
                    <div class="patient-feature-card">
                        <span>01</span>
                        <h3>Simple Access</h3>
                        <p>Clear interfaces that help patients and caregivers find the information they need quickly.</p>
                    </div>

                    <div class="patient-feature-card">
                        <span>02</span>
                        <h3>Trusted Information</h3>
                        <p>Content can be organized, reviewed, and maintained so users receive reliable guidance.</p>
                    </div>

                    <div class="patient-feature-card">
                        <span>03</span>
                        <h3>Partner Support</h3>
                        <p>Tools can support healthcare and community partners with better communication and navigation.</p>
                    </div>
                </div>
            </div>
        </section>
    @endsection

    @php return; @endphp
@endif
@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?? $page->excerpt)

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="page-content">
        <div
            class="container prose"
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >{!! $page->body !!}</div>
    </section>
@endsection
