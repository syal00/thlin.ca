@extends('layouts.app')

@section('title', (($page?->meta_title ?: 'Contact')).' - '.$thlin['name'])
@section('meta_description', $page?->meta_description ?: 'Contact thehealthline.ca Information Network.')

@if (! empty($page?->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('content')
    <section class="contact-page-section">
        <div class="contact-page-container">
            <div class="contact-page-header">
                <span class="section-kicker">Contact THLIN</span>
                <h1>Let's Connect</h1>
                <p>Contact thehealthline.ca Information Network.</p>
            </div>

            <div class="contact-page-grid">
                <div class="contact-form-card">
                    <div class="section-heading">
                        <span class="section-kicker blue">Message</span>
                        <h2>Send us a message</h2>
                        <p>Fill out the form and our team will get back to you as soon as possible.</p>
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

                        <button type="submit" class="contact-submit contact-submit-btn">
                            Send Message
                        </button>
                    </form>
                </div>

                <aside class="contact-info-card">
                    <span class="contact-card-icon">+</span>

                    <div class="contact-info-block">
                        <span class="contact-label">Address</span>
                        <p>201 King St, London, ON N6C 1C9</p>
                    </div>

                    <div class="contact-info-block">
                        <span class="contact-label">Phone</span>
                        <p>
                            <a href="tel:+15194347101">519-434-7101</a>
                        </p>
                    </div>

                    <div class="contact-info-block">
                        <span class="contact-label">Email</span>
                        <p>
                            <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a>
                        </p>
                    </div>

                    <div class="contact-info-note">
                        <h3>Need help finding services?</h3>
                        <p>
                            Use the search page or contact our team for support with THLIN resources and navigation.
                        </p>
                        <a href="{{ route('search') }}">Search THLIN Resources</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
