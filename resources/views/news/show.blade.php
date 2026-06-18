@extends('layouts.app')

@section('title', $post->title.' - '.$thlin['name'])

@section('hero')
    @include('partials.page-header', [
        'editable' => false,
        'eyebrow' => 'News',
        'heroTitle' => $post->title,
        'heroSubtitle' => $post->published_at
            ? $post->published_at->format('F j, Y').($post->location ? ' · '.$post->location : '')
            : null,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'News', 'url' => route('pages.show', ['section' => 'about', 'page' => 'news'])],
            ['label' => $post->title, 'current' => true],
        ],
        'hideDefaultActions' => true,
    ])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                <article class="cms-content">
                    <h1 @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'title', 'type' => 'text'])>{{ $post->title }}</h1>

                    @if ($post->excerpt || auth()->check())
                        <p class="news-excerpt" @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $post->excerpt !!}</p>
                    @endif

                    @auth
                        <div @include('partials.inline-edit-attrs', ['model' => 'news', 'id' => $post->id, 'field' => 'body', 'type' => 'richtext'])>
                            @include('partials.cms-body', ['html' => $post->body])
                        </div>
                    @else
                        @include('partials.cms-body', ['html' => $post->body])
                    @endauth
                </article>

                <p class="back-link">
                    <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">&larr; Back to News</a>
                </p>
            </div>
        </div>
    </section>
@endsection
