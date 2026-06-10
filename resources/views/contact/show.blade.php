@extends('layouts.app')

@section('title', 'Contact - '.$thlin['name'])

@section('content')
    <div class="page-title light-background">
        <div class="breadcrumbs">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active current">Contact</li>
                </ol>
            </nav>
        </div>
        <div class="title-wrapper">
            <h1>{{ $page->title }}</h1>
            <p>Get in touch with our team.</p>
        </div>
    </div>

    <section id="contact" class="contact section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            <div class="row g-4 contact-grid">
                <div class="col-lg-5">
                    <div class="contact-panel" data-aos="fade-up" data-aos-delay="150">
                        <div class="panel-header">
                            <span class="badge-label">Get in touch</span>
                            <h3 class="panel-title">Reach Out to Our Team</h3>
                        </div>

                        <div class="prose mb-4">
                            {!! $page->body !!}
                        </div>

                        <ul class="contact-list">
                            <li class="contact-list-item">
                                <div class="item-icon"><i class="bi bi-geo-alt"></i></div>
                                <div class="item-content">
                                    <span class="item-label">Head Office</span>
                                    <p class="item-value">{{ $thlin['address'] }}</p>
                                </div>
                            </li>
                            <li class="contact-list-item">
                                <div class="item-icon"><i class="bi bi-telephone"></i></div>
                                <div class="item-content">
                                    <span class="item-label">Phone</span>
                                    <p class="item-value"><a href="tel:{{ preg_replace('/\D/', '', $thlin['contact_phone']) }}">{{ $thlin['contact_phone'] }}</a></p>
                                </div>
                            </li>
                            <li class="contact-list-item">
                                <div class="item-icon"><i class="bi bi-envelope"></i></div>
                                <div class="item-content">
                                    <span class="item-label">Email</span>
                                    <p class="item-value"><a href="mailto:{{ $thlin['contact_email'] }}">{{ $thlin['contact_email'] }}</a></p>
                                </div>
                            </li>
                        </ul>

                        <div class="panel-footer">
                            <span class="footer-label">Follow us</span>
                            <div class="social-links">
                                <a href="{{ $thlin['social']['twitter'] }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                                <a href="{{ $thlin['social']['youtube'] }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                                <a href="{{ $thlin['social']['thehealthline'] }}" target="_blank" rel="noopener" aria-label="thehealthline.ca"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="form-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header">
                            <h3 class="card-title">Send Us a Message</h3>
                            <p class="card-description">Fill out the form below and our team will get back to you shortly.</p>
                        </div>

                        <form method="post" action="{{ route('contact.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="name">Name <span aria-hidden="true">*</span></label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label for="email">Email Address <span aria-hidden="true">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="organization">Organization</label>
                                        <input type="text" id="organization" name="organization" class="form-control" value="{{ old('organization') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="message">Message <span aria-hidden="true">*</span></label>
                                        <textarea id="message" name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                                        @error('message')<span class="field-error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-solid">Send message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
