@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?: $page->hero_subtitle)

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @php
        $customBreadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
        ];

        if ($page->parent) {
            $customBreadcrumbs[] = [
                'label' => $page->parent->title,
                'url' => url($page->parent->full_url),
            ];
        }

        $customBreadcrumbs[] = [
            'label' => $page->hero_title ?: $page->title,
            'current' => true,
        ];
    @endphp

    @include('partials.page-header', [
        'page' => $page,
        'eyebrow' => $page->parent ? $page->parent->title : 'THLIN Resource',
        'breadcrumbs' => $customBreadcrumbs,
    ])
@endsection

@section('content')
    <section class="custom-page-section">
        <div class="inner-container">
            <div class="custom-page-layout">
                <main class="custom-page-main">
                    @if (trim(strip_tags($page->body ?? '')) !== '' || auth()->check())
                        <article class="inner-content-card content-shell cms-content">
                            @auth
                                <div @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                                    @include('partials.cms-body', ['html' => $page->body])
                                </div>
                            @else
                                @include('partials.cms-body', ['html' => $page->body])
                            @endauth

                            @include('partials.page-updated', ['page' => $page])
                        </article>
                    @else
                        <article class="content-shell cms-content cms-empty-state">
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

    @include('partials.page-cta')
@endsection
