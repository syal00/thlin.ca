@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.page-header', ['page' => $page, 'eyebrow' => 'News'])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @if ($page->body)
                    <div class="cms-content">@include('partials.cms-body', ['html' => $page->body])</div>
                @endif

                @include('partials.page-updated', ['page' => $page])

                <ul class="news-list">
                    @forelse ($posts as $post)
                        <li class="news-item">
                            <h2>
                                <a href="{{ $post->url() }}">
                                    <span @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'title', 'type' => 'text'])>{{ $post->title }}</span>
                                </a>
                            </h2>
                            @if ($post->published_at)
                                <p class="news-meta">{{ $post->published_at->format('F j, Y') }}@if ($post->location) &middot; {{ $post->location }}@endif</p>
                            @endif
                            <p @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'excerpt', 'type' => 'textarea'])>{{ $post->excerpt }}</p>
                            <a href="{{ $post->url() }}">Read more</a>
                        </li>
                    @empty
                        <li class="news-item">
                            <p>No news posts yet. Check back soon for updates from THLIN.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>

    @include('partials.page-cta')
@endsection
