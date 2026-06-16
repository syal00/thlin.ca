@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?: $page->hero_subtitle)

@section('content')
    @auth
        <div class="cms-admin-edit-banner">
            <span>Editing this page in the CMS</span>
            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary btn-sm">Open Page Editor</a>
        </div>
    @endauth

    <section class="inner-hero">
        <div class="inner-container">
            <nav class="inner-breadcrumb breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>

                @if ($page->parent)
                    <span class="breadcrumb-sep" aria-hidden="true">/</span>
                    <a href="{{ url($page->parent->full_url) }}">
                        {{ $page->parent->title }}
                    </a>
                @endif

                <span class="breadcrumb-sep" aria-hidden="true">/</span>
                <span aria-current="page">{{ $page->title }}</span>
            </nav>

            <div class="inner-hero-content">
                <span class="inner-eyebrow">
                    {{ $page->parent ? $page->parent->title : 'THLIN Resource' }}
                </span>

                <h1 @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_title', 'type' => 'text'])>{{ $page->hero_title ?: $page->title }}</h1>

                @if ($page->hero_subtitle || $page->meta_description || auth()->check())
                    <p @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'hero_subtitle', 'type' => 'textarea'])>{{ $page->hero_subtitle ?: ($page->meta_description ?? '') }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="custom-page-section">
        <div class="inner-container">
            <div class="custom-page-layout">
                <main class="custom-page-main">
                    @if (trim(strip_tags($page->body ?? '')) !== '')
                        <article class="inner-content-card content-shell cms-content">
                            @include('partials.cms-body', ['html' => $page->body])
                        </article>
                    @else
                        <article class="content-shell cms-content cms-empty-state">
                            @auth
                                <h2>Content not added yet</h2>
                                <p>
                                    This page has been created in the CMS. Add content from the admin editor to complete this page.
                                </p>

                                <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                                    Edit this page
                                </a>
                            @else
                                <h2>Content coming soon</h2>
                                <p>
                                    Information for this page will be added soon.
                                </p>
                            @endauth
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
                                        {{ $childPage->menu_label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    <section class="inner-cta-section">
        <div class="inner-container">
            <div class="inner-cta">
                <div>
                    <span class="section-eyebrow">Get Started</span>

                    <h2>Ready to connect with THLIN?</h2>

                    <p>
                        Contact our team to learn more about our digital health information tools and partnership support.
                    </p>
                </div>

                <div class="inner-cta-actions">
                    <a href="{{ url('/contact') }}" class="btn btn-light">
                        Contact Us
                    </a>

                    <a href="{{ url('/products-services') }}" class="btn btn-outline-light">
                        Explore Products &amp; Services
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
