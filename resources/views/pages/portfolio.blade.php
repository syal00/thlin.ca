@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

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
                <div class="cms-content">@include('partials.cms-body', ['html' => $page->body])</div>
                @auth
                    <p class="cms-inline-edit-note">Full page content is edited in the CMS panel using <a href="{{ route('admin.pages.edit', $page) }}">Edit This Page in CMS</a>.</p>
                @endauth
            </div>
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="home-section section-alt">
            <div class="section-container">
                <div class="section-heading">
                    <span class="section-kicker blue">Featured Work</span>
                    <h2>Highlighted Projects</h2>
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
                    <h2>Past Projects</h2>
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
                                <p @include('partials.inline-edit-attrs', ['model' => 'portfolio', 'id' => $item->id, 'field' => 'excerpt', 'type' => 'textarea'])>{{ $item->excerpt }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    @include('partials.page-cta', [
        'ctaTitle' => 'Interested in Collaborating?',
        'ctaText' => 'We work with partners to improve information systems and connect the people of Ontario to relevant health and community services.',
        'ctaPrimary' => 'Contact Us',
        'ctaSecondary' => 'Explore Products & Services',
    ])
@endsection
