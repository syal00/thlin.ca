@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('hero')
    @include('partials.page-header', ['page' => $page, 'eyebrow' => 'News'])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @if ($page->body)
                    <div class="cms-content">{!! $page->body !!}</div>
                @endif

                <ul class="news-list">
                    @forelse ($posts as $post)
                        <li class="news-item">
                            <h2><a href="{{ $post->url() }}">{{ $post->title }}</a></h2>
                            @if ($post->published_at)
                                <p class="news-meta">{{ $post->published_at->format('F j, Y') }}@if ($post->location) &middot; {{ $post->location }}@endif</p>
                            @endif
                            <p>{{ $post->excerpt }}</p>
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
@endsection
