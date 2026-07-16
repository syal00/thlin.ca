@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('content')
    <section class="portfolio-page">
        <div class="portfolio-container">

            <div class="portfolio-header">
                <div class="portfolio-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Products &amp; Services</a>
                    <span>/</span>
                    <span>Portfolio</span>
                </div>

                <span class="section-kicker">Featured Work</span>
                <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'title', 'type' => 'text'])>{{ $page->title }}</h1>
                <p>
                    Mapping the mosaic of services available within your community and presenting the information effectively takes careful work.
                    THLIN helps organizations build patient-centred digital tools that are simple, usable, and information-rich.
                </p>
            </div>

            <section class="portfolio-featured-section">
                <div class="portfolio-section-heading">
                    <span class="section-kicker">Projects</span>
                    <h2>Explore selected THLIN work</h2>
                    <p>
                        Examples of digital health information tools, service navigation websites, and community information platforms.
                    </p>
                </div>

                <div class="portfolio-project-grid">
                    @foreach ($featured as $project)
                        <article class="portfolio-project-card" data-animate="fade-up">
                            <div class="portfolio-project-media">
                                @if (!empty($project->image))
                                    <img
                                        src="{{ $project->imageUrl() }}"
                                        alt="{{ $project->title }}"
                                        data-editable-image="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="image"
                                    >
                                @else
                                    <div
                                        class="portfolio-image-placeholder"
                                        data-editable-image="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="image"
                                    >
                                        <span>Project image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="portfolio-project-body">
                                <h3 @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $project->id, 'field' => 'title', 'type' => 'text'])>{{ $project->title }}</h3>
                                <p @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $project->id, 'field' => 'excerpt', 'type' => 'richtext'])>{{ trim(strip_tags((string) $project->excerpt)) }}</p>

                                @if (!empty($project->url))
                                    <a href="{{ $project->url }}" class="portfolio-project-link" target="_blank" rel="noopener">
                                        View project
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach

                    @foreach ($past as $project)
                        <article class="portfolio-project-card" data-animate="fade-up">
                            <div class="portfolio-project-media">
                                @if (!empty($project->image))
                                    <img
                                        src="{{ $project->imageUrl() }}"
                                        alt="{{ $project->title }}"
                                        data-editable-image="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="image"
                                    >
                                @else
                                    <div
                                        class="portfolio-image-placeholder"
                                        data-editable-image="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="image"
                                    >
                                        <span>Project image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="portfolio-project-body">
                                <h3 @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $project->id, 'field' => 'title', 'type' => 'text'])>{{ $project->title }}</h3>
                                <p @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $project->id, 'field' => 'excerpt', 'type' => 'richtext'])>{{ trim(strip_tags((string) $project->excerpt)) }}</p>

                                @if (!empty($project->url))
                                    <a href="{{ $project->url }}" class="portfolio-project-link" target="_blank" rel="noopener">
                                        View project
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @include('partials.page-updated', ['page' => $page])
            </section>

        </div>
    </section>

    @include('partials.cta-section')
@endsection
