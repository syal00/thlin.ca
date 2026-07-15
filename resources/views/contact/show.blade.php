@extends('layouts.app')

@section('title', (($page?->meta_title ?: 'Contact')).' - '.$thlin['name'])
@section('meta_description', $page?->meta_description ?: 'Contact thehealthline.ca Information Network.')

@if (! empty($page?->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.hero-page', [
        'page' => $page,
        'editable' => false,
        'heroTitle' => "Let's Connect",
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Contact', 'current' => true],
        ],
    ])
@endsection

@section('content')
    <section class="t-prose">
        <div class="t-container t-contact-grid">
            <div class="t-contact-form">
                <h2>Send us a message</h2>
                <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                    @csrf

                    <div class="t-form-group">
                        <label for="name">Name <span>*</span></label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                        @error('name')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="t-form-group">
                        <label for="email">Email Address <span>*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                        @error('email')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="t-form-group">
                        <label for="organization">Organization</label>
                        <input id="organization" type="text" name="organization" value="{{ old('organization') }}" placeholder="Enter your organization name">
                        @error('organization')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="t-form-group">
                        <label for="message">Message <span>*</span></label>
                        <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="contact-submit t-btn t-btn-primary">
                        Send Message
                    </button>
                </form>
            </div>

            {{-- Head Office: matches the source site exactly — address, phone, email only. --}}
            <aside class="t-contact-office">
                <h2>Head Office</h2>

                <div class="t-contact-info-item">
                    <span>Address</span>
                    <p>201 King St, London, ON N6C 1C9</p>
                </div>

                <div class="t-contact-info-item">
                    <span>Phone</span>
                    <a href="tel:5196605910">519-660-5910</a>
                </div>

                <div class="t-contact-info-item">
                    <span>Email</span>
                    <a href="mailto:admin@thehealthline.ca">admin@thehealthline.ca</a>
                </div>
            </aside>
        </div>
    </section>
@endsection
