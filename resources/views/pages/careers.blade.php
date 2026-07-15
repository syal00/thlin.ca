@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.hero-page', ['page' => $page])
@endsection

@section('content')
    <section class="t-prose">
        <div class="t-container">
            @if ($page->body || auth()->check())
                @auth
                    <div class="t-prose-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                        @include('partials.cms-body', ['html' => $page->body])
                    </div>
                @else
                    <div class="t-prose-content">@include('partials.cms-body', ['html' => $page->body])</div>
                @endauth
            @endif

            @include('partials.page-updated', ['page' => $page])

            <div class="job-list">
                @forelse ($jobs as $job)
                    @include('partials.card-job', ['job' => $job])
                @empty
                    <article class="job-card">
                        <h2>No open positions right now</h2>
                        <p>We are not actively hiring at the moment. Please check back later or contact us to learn more about future opportunities.</p>
                    </article>
                @endforelse
            </div>
        </div>
    </section>

    @include('partials.cta-section')
@endsection
