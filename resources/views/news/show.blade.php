@extends('layouts.app')

@section('title', $post->title.' - '.$thlin['name'])

@section('hero')
    @include('partials.hero-page', [
        'record' => $post,
        'model' => 'news',
        'eyebrow' => 'News',
        'heroSubtitle' => $post->published_at
            ? $post->published_at->format('F j, Y').($post->location ? ' · '.$post->location : '')
            : null,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'News', 'url' => route('pages.show', ['section' => 'about', 'page' => 'news'])],
            ['label' => $post->title, 'current' => true],
        ],
    ])
@endsection

@section('content')
    <section class="t-prose">
        <div class="t-container">
            <article class="t-prose-content">
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
    </section>
@endsection
