@extends('layouts.app')

@section('title', 'Contact - '.$thlin['name'])

@section('content')
    <div class="page-header">
        <div class="container">
            <h1>{{ $page->title }}</h1>
        </div>
    </div>

    <section class="page-content">
        <div class="container">
            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            <div class="contact-layout">
                <div class="prose">
                    {!! $page->body !!}
                </div>

                <form class="contact-form admin-card" method="post" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name <span aria-hidden="true">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address <span aria-hidden="true">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="organization">Organization</label>
                        <input type="text" id="organization" name="organization" value="{{ old('organization') }}">
                    </div>
                    <div class="form-group">
                        <label for="message">Message <span aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                        @error('message')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Send message</button>
                </form>
            </div>

            <div class="contact-details" style="margin-top: 2rem;">
                <h2>Head Office</h2>
                <p><strong>Address:</strong> {{ $thlin['address'] }}</p>
                <p><strong>Phone:</strong> <a href="tel:{{ preg_replace('/\D/', '', $thlin['contact_phone']) }}">{{ $thlin['contact_phone'] }}</a></p>
                <p><strong>Email:</strong> <a href="mailto:{{ $thlin['contact_email'] }}">{{ $thlin['contact_email'] }}</a></p>
            </div>
        </div>
    </section>
@endsection
