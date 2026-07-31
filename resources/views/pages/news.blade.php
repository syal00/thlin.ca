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
            @include('partials.page-custom-html', ['html' => $page->custom_html])
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

            <ul class="news-list">
                @forelse ($posts as $post)
                    @include('partials.card-news', ['post' => $post])
                @empty
                    <li class="news-item">
                        <p>No news posts yet. Check back soon for updates from THLIN.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </section>

    @include('partials.cta-section')
@endsection
