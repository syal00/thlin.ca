@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.page-header', [
        'page' => $page,
        'eyebrow' => 'Portfolio',
    ])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @auth
                    <div class="cms-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                        @include('partials.cms-body', ['html' => $page->body])
                    </div>
                @else
                    <div class="cms-content">@include('partials.cms-body', ['html' => $page->body])</div>
                @endauth
                @include('partials.page-updated', ['page' => $page])
            </div>
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="home-section section-alt">
            <div class="section-container">
                <div class="section-heading">
                    <span class="section-kicker blue">Featured Work</span>
                    <h2>@include('partials.site-setting', ['key' => 'portfolio_featured_title', 'tag' => 'span', 'type' => 'text'])</h2>
                </div>

                <div class="portfolio-grid">
                    @foreach ($featured as $item)
                        @include('partials.portfolio-card', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($past->isNotEmpty())
        <section class="home-section section-light">
            <div class="section-container">
                <div class="section-heading">
                    <span class="section-kicker blue">Archive</span>
                    <h2>@include('partials.site-setting', ['key' => 'portfolio_past_title', 'tag' => 'span', 'type' => 'text'])</h2>
                </div>

                <ul class="portfolio-list">
                    @foreach ($past as $item)
                        <li class="portfolio-list-item">
                            @if ($item->image)
                                <img
                                    src="{{ $item->imageUrl() }}"
                                    alt="{{ $item->title }}"
                                    data-editable-image="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="image"
                                >
                            @else
                                <div
                                    class="portfolio-placeholder"
                                    data-editable-image="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="image"
                                >Image</div>
                            @endif
                            <div>
                                <h3>
                                    <a href="{{ $item->url }}" target="_blank" rel="noopener">
                                        <span @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $item->id, 'field' => 'title', 'type' => 'text'])>{{ $item->title }}</span>
                                    </a>
                                </h3>
                                <p @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $item->id, 'field' => 'excerpt', 'type' => 'richtext'])>{!! $item->excerpt !!}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @include('partials.page-cta', ['settingPrefix' => 'portfolio'])
@endsection
