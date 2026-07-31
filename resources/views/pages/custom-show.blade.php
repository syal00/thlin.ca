@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?: $page->hero_subtitle ?: '')

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.hero-page', [
        'page' => $page,
        'heroTitle' => $page->hero_title ?: $page->title,
        'eyebrow' => $page->parent ? $page->parent->title : 'THLIN Resource',
    ])
@endsection

@section('content')
    <section class="t-prose custom-page-section">
        <div class="t-container">
            <div class="custom-page-layout">
                <main class="custom-page-main">
                    @php
                        $hasBody = trim(strip_tags($page->body ?? '')) !== '';
                        $hasCustomHtml = filled(trim($page->custom_html ?? ''));
                        $hasContent = $hasBody || $hasCustomHtml;
                    @endphp

                    @if ($hasContent || auth()->check())
                        <article class="t-prose-content">
                            @if ($hasCustomHtml)
                                <div class="custom-html-content">
                                    {!! $page->custom_html !!}
                                </div>
                            @endif

                            @if ($hasBody)
                                @auth
                                    <div @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                                        @include('partials.cms-body', ['html' => $page->body])
                                    </div>
                                @else
                                    @include('partials.cms-body', ['html' => $page->body])
                                @endauth
                            @endif

                            @include('partials.page-updated', ['page' => $page])
                        </article>
                    @else
                        <article class="t-prose-content cms-empty-state">
                            <h2>Content coming soon</h2>
                            <p>Information for this page will be added soon.</p>
                        </article>
                    @endif
                </main>

                @if ($page->parent && $page->parent->visibleChildren()->count())
                    <aside class="custom-page-sidebar">
                        <div class="sidebar-card">
                            <h2>{{ $page->parent->title }}</h2>
                            <p>Explore related pages in this section.</p>

                            <div class="sidebar-links">
                                @foreach ($page->parent->visibleChildren as $childPage)
                                    <a href="{{ url($childPage->full_url) }}" @class(['active' => $childPage->id === $page->id])>
                                        <span @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $childPage->id, 'field' => 'navigation_label', 'type' => 'text'])>{{ $childPage->menu_label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    @include('partials.cta-section')
@endsection
