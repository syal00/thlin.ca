@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.page-header', ['page' => $page, 'eyebrow' => 'Careers'])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @if ($page->body || auth()->check())
                    @auth
                        <div class="cms-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                            @include('partials.cms-body', ['html' => $page->body])
                        </div>
                    @else
                        <div class="cms-content">@include('partials.cms-body', ['html' => $page->body])</div>
                    @endauth
                @endif

                @include('partials.page-updated', ['page' => $page])

                <div class="job-list">
                    @forelse ($jobs as $job)
                        <article class="job-card" id="{{ $job->slug }}">
                            <h2 @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'title', 'type' => 'text'])>{{ $job->title }}</h2>
                            <p class="job-meta">
                                <span @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'location', 'type' => 'text'])>{{ $job->location ?: 'Location TBD' }}</span>
                                @if ($job->employment_type)
                                    <span aria-hidden="true"> &middot; </span>
                                    <span @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'employment_type', 'type' => 'text'])>{{ $job->employment_type }}</span>
                                @endif
                            </p>
                            @if ($job->posted_at || $job->closes_at)
                                <p class="job-meta">
                                    @if ($job->posted_at)Posted {{ $job->posted_at->format('F j, Y') }}@endif
                                    @if ($job->closes_at) &middot; Closes {{ $job->closes_at->format('F j, Y') }}@endif
                                </p>
                            @endif
                            @auth
                                <div class="cms-content" @include('partials.inline-edit-attrs', ['model' => 'career', 'id' => $job->id, 'field' => 'body', 'type' => 'richtext'])>
                                    @include('partials.cms-body', ['html' => $job->body])
                                </div>
                            @else
                                <div class="cms-content">@include('partials.cms-body', ['html' => $job->body])</div>
                            @endauth
                        </article>
                    @empty
                        <article class="job-card">
                            <h2>No open positions right now</h2>
                            <p>We are not actively hiring at the moment. Please check back later or contact us to learn more about future opportunities.</p>
                        </article>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    @include('partials.page-cta')
@endsection
