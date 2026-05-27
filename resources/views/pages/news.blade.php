@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="page-content">
        <div class="container prose">
            {!! $page->body !!}
        </div>
        <div class="container">
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
                    <li><p>No news posts yet.</p></li>
                @endforelse
            </ul>
        </div>
    </section>
@endsection
