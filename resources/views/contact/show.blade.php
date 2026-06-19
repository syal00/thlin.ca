@extends('layouts.app')

@section('title', (($page?->meta_title ?: 'Contact')).' - '.$thlin['name'])
@section('meta_description', $page?->meta_description ?: 'Contact thehealthline.ca Information Network.')

@if (! empty($page?->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @if ($page)
        @include('partials.page-header', [
            'page' => $page,
            'editable' => true,
            'eyebrow' => 'Contact THLIN',
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Contact', 'current' => true],
            ],
            'hideDefaultActions' => true,
        ])
    @else
        @include('partials.page-header', [
            'editable' => false,
            'eyebrow' => 'Contact THLIN',
            'heroTitle' => "Let's Connect",
            'heroSubtitle' => 'We work with partners to improve information systems and connect people across Ontario to trusted health and community services.',
            'breadcrumbs' => [
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Contact', 'current' => true],
            ],
            'hideDefaultActions' => true,
        ])
    @endif
@endsection

@section('content')
    <section class="home-section section-light contact-page contact-page-section">
        <div class="section-container">
            <div class="contact-grid contact-page-grid">
                <div class="contact-form-card content-shell">
                    <div class="section-heading">
                        <span class="section-kicker blue">Message</span>
                        @include('partials.site-setting', ['key' => 'contact_form_title', 'tag' => 'h2', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'contact_form_subtitle', 'tag' => 'p', 'type' => 'textarea'])
                    </div>

                    @include('partials.page-updated', ['page' => $page])

                    @if (session('success'))
                        <div class="contact-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name <span>*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                            @error('name')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span>*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                            @error('email')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="organization">Organization</label>
                            <input id="organization" type="text" name="organization" value="{{ old('organization') }}" placeholder="Enter your organization name">
                            @error('organization')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="message">Message <span>*</span></label>
                            <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <small>{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="contact-submit">
                            Send Message
                        </button>
                    </form>
                </div>

                <aside class="contact-info-panel info-panel contact-info-card">
                    <div class="contact-info-icon">+</div>

                    @include('partials.site-setting', ['key' => 'contact_office_heading', 'tag' => 'h2', 'type' => 'text'])

                    <div class="contact-info-item">
                        <span>Address</span>
                        <p>@include('partials.site-setting', ['key' => 'contact_address', 'tag' => 'span', 'type' => 'text'])</p>
                    </div>

                    <div class="contact-info-item">
                        <span>Phone</span>
                        <a href="tel:{{ preg_replace('/\D+/', '', \App\Models\SiteSetting::getValue('contact_phone', '5196605910')) }}">@include('partials.site-setting', ['key' => 'contact_phone', 'tag' => 'span', 'type' => 'text'])</a>
                    </div>

                    <div class="contact-info-item">
                        <span>Email</span>
                        <a href="mailto:{{ \App\Models\SiteSetting::getValue('contact_email', 'admin@thehealthline.ca') }}">@include('partials.site-setting', ['key' => 'contact_email', 'tag' => 'span', 'type' => 'text'])</a>
                    </div>

                    <div class="contact-note">
                        @include('partials.site-setting', ['key' => 'contact_note_title', 'tag' => 'strong', 'type' => 'text'])
                        @include('partials.site-setting', ['key' => 'contact_note_text', 'tag' => 'p', 'type' => 'textarea'])
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
