@extends('layouts.app')

@section('title', 'Contact - '.$thlin['name'])
@section('meta_description', 'Contact thehealthline.ca Information Network.')

@section('content')
    <section class="contact-page">
        <div class="container">
            <div class="contact-hero">
                <span class="section-kicker blue">Contact THLIN</span>

                <h1>Let’s Connect</h1>

                <p>
                    We work with partners to improve information systems and connect people across Ontario
                    to trusted health and community services.
                </p>
            </div>

            <div class="contact-grid">
                <div class="contact-form-card">
                    <h2>Send us a message</h2>
                    <p>Tell us how we can help. We’ll get back to you as soon as possible.</p>

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

                <aside class="contact-info-panel">
                    <div class="contact-info-icon">+</div>

                    <h2>Head Office</h2>

                    <div class="contact-info-item">
                        <span>Address</span>
                        <p>201 King St, London, ON N6C 1C9</p>
                    </div>

                    <div class="contact-info-item">
                        <span>Phone</span>
                        <a href="tel:5196605910">519-660-5910</a>
                    </div>

                    <div class="contact-info-item">
                        <span>Email</span>
                        <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a>
                    </div>

                    <div class="contact-note">
                        <strong>Working with THLIN?</strong>
                        <p>
                            Contact us about partnerships, digital tools, service directories,
                            portals, and information management support.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection