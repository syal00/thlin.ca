@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?: $page->hero_subtitle)

@section('hero')
    @include('partials.page-header', [
        'page' => $page,
        'eyebrow' => 'THLIN Resource',
        'titleField' => 'hero_title',
        'subtitleField' => 'hero_subtitle',
        'heroTitle' => $page->hero_title ?: $page->title,
        'heroSubtitle' => $page->hero_subtitle ?: $page->meta_description,
    ])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                <article class="cms-content">
                    {!! $page->body !!}
                </article>
            </div>
        </div>
    </section>

    @include('partials.page-cta')
@endsection
