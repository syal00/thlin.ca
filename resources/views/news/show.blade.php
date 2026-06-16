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
                    {!! $post->body !!}
                </article>

                <p class="back-link">
                    <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">&larr; Back to News</a>
                </p>
            </div>
        </div>
    </section>
@endsection
