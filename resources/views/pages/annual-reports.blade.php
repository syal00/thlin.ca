@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.page-header', ['page' => $page, 'eyebrow' => 'Reports'])
@endsection

@section('content')
    <section class="home-section section-light annual-reports-section">
        <div class="section-container">
            <div class="content-shell">
                @if ($page->body || auth()->check())
                    @auth
                        <div class="cms-content annual-report-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                            @include('partials.cms-body', ['html' => $page->body])
                        </div>
                    @else
                        <div class="cms-content annual-report-content">@include('partials.cms-body', ['html' => $page->body])</div>
                    @endauth
                @endif

                @include('partials.page-updated', ['page' => $page])
            </div>
        </div>
    </section>

    @include('partials.page-cta')
@endsection
