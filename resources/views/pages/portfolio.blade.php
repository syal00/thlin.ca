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
            <div
                class="content-shell cms-content"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="body"
            >{!! $page->body !!}</div>
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
                        <a href="{{ $item->url }}" class="portfolio-card" target="_blank" rel="noopener">
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
                                >Click to add image</div>
                            @endif
                            <div class="portfolio-card-body">
                                <h3
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="title"
                                >{{ $item->title }}</h3>
                                <p
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="excerpt"
                                >{{ $item->excerpt }}</p>
                                <strong>View project</strong>
                            </div>
                        </a>
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
                                <a href="{{ $item->url }}" target="_blank" rel="noopener"><strong
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="title"
                                >{{ $item->title }}</strong></a>
                                <p
                                    data-editable="true"
                                    data-model="portfolio"
                                    data-id="{{ $item->id }}"
                                    data-field="excerpt"
                                >{{ $item->excerpt }}</p>
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
