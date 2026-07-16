@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('content')

@if ($page->section === 'about' && $page->slug === 'us')

<section class="about-hero">
    <div class="container about-hero-grid">
        <div>
            <div class="about-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>About</span>
                <span>/</span>
                <span>{{ $page->title }}</span>
            </div>

            <span class="section-kicker">About THLIN</span>

            <h1
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="title"
            >{{ $page->title }}</h1>

            @if ($page->excerpt)
                <p
                    class="about-hero-text"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="excerpt"
                >{{ $page->excerpt }}</p>
            @endif

            <div class="about-actions">
                <a href="{{ route('contact') }}" class="about-btn about-btn-primary">Contact Us</a>
                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}" class="about-btn about-btn-secondary">Meet Our Board</a>
            </div>
        </div>

        <aside class="about-hero-card" data-animate="fade-up">
            <span class="about-card-label">Founded</span>
            <strong>2001</strong>
            <p>
                Supporting Ontario communities with trusted digital health and community service information.
            </p>

            <div class="about-mini-stats">
                <div>
                    <span>20+</span>
                    <p>Years of service</p>
                </div>
                <div>
                    <span>Ontario</span>
                    <p>Service focus</p>
                </div>
            </div>
        </aside>
    </div>
</section>

<section class="about-content-section">
    <div class="container about-content-grid">
        <article class="about-main-card" data-animate="fade-up">
            <span class="section-kicker blue">Our Story</span>

            <div
                class="about-rich-content"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="body"
            >
                {!! $page->body !!}
            </div>

            @if (!empty($page->updated_at))
                <div class="page-meta-row">
                    <span class="page-last-updated">
                        Last updated: {{ $page->updated_at->format('F j, Y') }}
                    </span>
                </div>
            @endif

            <a href="{{ route('contact') }}" class="about-story-btn">Contact Us</a>
        </article>

        <aside class="about-side-card" data-animate="fade-up">
            <h2>About THLIN</h2>
            <ul>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">Our Story</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}">Board</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}">Annual Reports</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">Careers</a></li>
            </ul>
        </aside>
    </div>
</section>

<section class="related-pages-section" aria-label="Related Pages">
    <div class="container">
        <div class="related-pages-header">
            <span class="section-kicker blue">Related Pages</span>
            <h2>Learn more about THLIN</h2>
            <p>Explore our organization, governance, updates, and opportunities.</p>
        </div>

        <div class="related-pages-grid">
            <a class="related-page-card" href="{{ route('pages.show', ['section' => 'about', 'page' => 'board']) }}" data-animate="fade-up">
                <span>01</span>
                <h3>Board</h3>
                <p>Learn more about THLIN leadership and governance.</p>
            </a>

            <a class="related-page-card" href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}" data-animate="fade-up">
                <span>02</span>
                <h3>Annual Reports</h3>
                <p>View organizational reports and updates.</p>
            </a>

            <a class="related-page-card" href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}" data-animate="fade-up">
                <span>03</span>
                <h3>News</h3>
                <p>Read the latest THLIN news and announcements.</p>
            </a>

            <a class="related-page-card" href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}" data-animate="fade-up">
                <span>04</span>
                <h3>Careers</h3>
                <p>Explore opportunities to work with THLIN.</p>
            </a>
        </div>
    </div>
</section>

@include('partials.page-cta')

@elseif (($section ?? $page->section) === 'products' || $page->section === 'products')

@php
    $isPatientPortals = $page->slug === 'patient-portals';
    $isSupportTraining = $page->slug === 'support-training';
    $isPortfolio = $page->slug === 'portfolio';
    $isResources = $page->slug === 'resources';
    $patientBodyHtml = (string) ($page->body ?? '');
    $patientBodyForDisplay = $patientBodyHtml;
    $supportBodyForDisplay = (string) ($page->body ?? '');
    $resourcesBodyForDisplay = (string) ($page->body ?? '');
    $resourcesImagePath = 'images/service-directories.jpg';
    $hasResourcesImage = file_exists(public_path($resourcesImagePath));
    $resourcesReadMoreUrl = route('pages.show', ['section' => 'products', 'page' => 'healthline']);
    $portfolioItems = collect();
    $patientSolutionsLinks = [
        ['label' => 'Behavioural Supports Ontario', 'url' => 'http://www.behaviouralsupportsontario.ca/'],
        ['label' => 'Caregiver Exchange', 'url' => 'https://www.caregiverexchange.ca/'],
        ['label' => 'South West Healthy Aging', 'url' => 'http://swhealthyaging.ca/'],
    ];

    if ($isPatientPortals) {
        if (preg_match('/<section class="content-section">\s*<h2>\s*Solutions in Action\s*<\/h2>(.*?)<\/section>/is', $patientBodyHtml, $solutionSectionMatch)) {
            if (preg_match_all('/<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>/is', $solutionSectionMatch[1], $solutionLinkMatches, PREG_SET_ORDER)) {
                $patientSolutionsLinks = collect($solutionLinkMatches)
                    ->map(fn ($match) => [
                        'url' => trim($match[1]),
                        'label' => trim(strip_tags($match[2])),
                    ])
                    ->filter(fn ($link) => !empty($link['url']) && !empty($link['label']))
                    ->values()
                    ->all();
            }

            $patientBodyForDisplay = str_replace($solutionSectionMatch[0], '', $patientBodyHtml);
        }

        $patientBodyForDisplay = str_replace('class="content-section"', 'class="content-section patient-section-card service-content-block"', $patientBodyForDisplay);
        $patientBodyForDisplay = preg_replace(
            '/<section class="content-section patient-section-card service-content-block">\s*<h2>\s*User-friendly\s*<\/h2>/i',
            '<section class="content-section patient-section-card service-content-block patient-feature-card"><h2>User-friendly</h2>',
            $patientBodyForDisplay,
            1
        );
    }

    if ($isSupportTraining) {
        $supportBodyForDisplay = str_replace('class="content-section"', 'class="content-section support-training-card service-content-block"', $supportBodyForDisplay);

        $contactPattern = '/<p>\s*To book training or request support:\s*<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>\s*<br\s*\/?>\s*For support with healthchat\.ca:\s*<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>\s*<\/p>/is';
        if (preg_match($contactPattern, $supportBodyForDisplay, $contactMatch)) {
            $contactHtml =
                '<div class="support-contact-grid">'
                . '<div class="support-contact-row">'
                . '<span>To book training or request support:</span>'
                . '<a href="' . e(trim($contactMatch[1])) . '">' . e(trim(strip_tags($contactMatch[2]))) . '</a>'
                . '</div>'
                . '<div class="support-contact-row">'
                . '<span>For support with healthchat.ca:</span>'
                . '<a href="' . e(trim($contactMatch[3])) . '">' . e(trim(strip_tags($contactMatch[4]))) . '</a>'
                . '</div>'
                . '</div>';

            $supportBodyForDisplay = preg_replace($contactPattern, $contactHtml, $supportBodyForDisplay, 1);
        }

        $tutorialPattern = '/<section class="content-section support-training-card service-content-block">\s*<h2>\s*Need Help Navigating thehealthline\.ca\?\s*<\/h2>(.*?)<\/section>/is';
        if (preg_match($tutorialPattern, $supportBodyForDisplay, $tutorialMatch)) {
            $tutorialInnerHtml = $tutorialMatch[1];
            $tutorialIntro = 'There is a regional thehealthline.ca website in every part of the province. Watch quick tutorial videos to learn how to submit, find, and share information.';

            if (preg_match('/<p>(.*?)<\/p>/is', $tutorialInnerHtml, $introMatch)) {
                $tutorialIntro = trim(strip_tags($introMatch[1]));
            }

            $tutorialCards = [];
            if (preg_match_all('/<div class="card">\s*<h3>(.*?)<\/h3>\s*<p>(.*?)<\/p>\s*<a[^>]*href="([^"]+)"[^>]*>(.*?)<\/a>\s*<\/div>/is', $tutorialInnerHtml, $cardMatches, PREG_SET_ORDER)) {
                foreach ($cardMatches as $cardMatch) {
                    $tutorialCards[] = [
                        'title' => trim(strip_tags($cardMatch[1])),
                        'description' => trim(strip_tags($cardMatch[2])),
                        'href' => trim($cardMatch[3]),
                        'label' => trim(strip_tags($cardMatch[4])),
                    ];
                }
            }

            $playlistHref = '#';
            if (preg_match('/<p>\s*<a[^>]*href="([^"]+)"[^>]*>\s*View full tutorial playlist\s*<\/a>\s*<\/p>/is', $tutorialInnerHtml, $playlistMatch)) {
                $playlistHref = trim($playlistMatch[1]);
            }

            if (count($tutorialCards) >= 3) {
                $tutorialHtml =
                    '<section class="support-tutorial-section">'
                    . '<div class="support-tutorial-header">'
                    . '<h2>Need Help Navigating thehealthline.ca?</h2>'
                    . '<p>' . e($tutorialIntro) . '</p>'
                    . '</div>'
                    . '<div class="support-tutorial-grid">';

                foreach ($tutorialCards as $tutorialCard) {
                    $tutorialHtml .=
                        '<article class="support-tutorial-card">'
                        . '<h3>' . e($tutorialCard['title']) . '</h3>'
                        . '<p>' . e($tutorialCard['description']) . '</p>'
                        . '<a href="' . e($tutorialCard['href']) . '" class="support-video-btn" target="_blank" rel="noopener">' . e($tutorialCard['label']) . '</a>'
                        . '</article>';
                }

                $tutorialHtml .=
                    '</div>'
                    . '<div class="support-playlist-action">'
                    . '<a href="' . e($playlistHref) . '" class="support-playlist-btn" target="_blank" rel="noopener">View full tutorial playlist</a>'
                    . '</div>'
                    . '</section>';

                $supportBodyForDisplay = preg_replace($tutorialPattern, $tutorialHtml, $supportBodyForDisplay, 1);
            }
        }
    }

    if ($isPortfolio) {
        $portfolioItems = \App\Models\PortfolioItem::query()->ordered()->get();

        if ($portfolioItems->isEmpty()) {
            $portfolioItems = collect([
                (object) [
                    'id' => null,
                    'title' => 'AES Wellness Portal',
                    'excerpt' => 'A culturally-driven education and wellness information portal supporting access to community resources and services.',
                    'url' => 'https://aeswellnessportal.ca/',
                    'image' => null,
                ],
                (object) [
                    'id' => null,
                    'title' => 'FamilyInfo',
                    'excerpt' => 'Helping families access local programming, services, and community information in one organized online space.',
                    'url' => 'https://familyinfo.ca/',
                    'image' => null,
                ],
                (object) [
                    'id' => null,
                    'title' => 'Age-Friendly Sarnia Lambton',
                    'excerpt' => 'Supporting community members with accessible information, local resources, and age-friendly service navigation.',
                    'url' => 'https://agefriendlysarnialambton.ca/',
                    'image' => null,
                ],
            ]);
        }
    }

    if ($isResources) {
        $resourcesBodyForDisplay = preg_replace('/^\s*<section[^>]*>\s*<article[^>]*>/i', '', $resourcesBodyForDisplay);
        $resourcesBodyForDisplay = preg_replace('/<\/article>\s*<\/section>\s*$/i', '', $resourcesBodyForDisplay);
        $resourcesBodyForDisplay = preg_replace(
            '/<a[^>]*href="[^"]*"[^>]*>(.*?)<\/a>/is',
            '<a href="' . e($resourcesReadMoreUrl) . '" class="resources-card-link">$1</a>',
            $resourcesBodyForDisplay,
            1
        );

        if (stripos($resourcesBodyForDisplay, 'resources-card-link') === false) {
            $resourcesBodyForDisplay .= '<p><a href="' . e($resourcesReadMoreUrl) . '" class="resources-card-link">Read more</a></p>';
        }
    }
@endphp

@if ($isPortfolio)
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
                <h1
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="title"
                >{{ $page->title }}</h1>
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
                    @foreach ($portfolioItems as $project)
                        <article class="portfolio-project-card" data-animate="fade-up">
                            <div class="portfolio-project-media">
                                @if (!empty($project->image))
                                    <img
                                        src="{{ method_exists($project, 'imageUrl') ? $project->imageUrl() : asset('storage/' . $project->image) }}"
                                        alt="{{ $project->title }}"
                                        @if (!empty($project->id))
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $project->id }}"
                                            data-field="image"
                                        @endif
                                    >
                                @else
                                    <div
                                        class="portfolio-image-placeholder"
                                        @if (!empty($project->id))
                                            data-editable-image="true"
                                            data-model="portfolio"
                                            data-id="{{ $project->id }}"
                                            data-field="image"
                                        @endif
                                    >
                                        <span>Project image</span>
                                    </div>
                                @endif
                            </div>

                            <div class="portfolio-project-body">
                                <h3
                                    @if (!empty($project->id))
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="title"
                                    @endif
                                >{{ $project->title }}</h3>
                                <p
                                    @if (!empty($project->id))
                                        data-editable="true"
                                        data-model="portfolio"
                                        data-id="{{ $project->id }}"
                                        data-field="excerpt"
                                    @endif
                                >{{ trim(strip_tags((string) ($project->excerpt ?? ''))) }}</p>

                                @if (!empty($project->url))
                                    <a href="{{ $project->url }}" class="portfolio-project-link" target="_blank" rel="noopener">
                                        View project
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

        </div>
    </section>

    @include('partials.page-cta')
@elseif ($isResources)
    <section class="resources-page">
        <div class="resources-container">

            <div class="resources-header">
                <div class="resources-breadcrumb">
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Products &amp; Services</a>
                    <span>/</span>
                    <span>Resources</span>
                </div>

                <span class="section-kicker">Resources</span>
                <h1>Service directories and digital resources</h1>
                <p>
                    Explore THLIN resources that support service navigation, community information,
                    and access to trusted health and social service information.
                </p>
            </div>

            <div class="resources-card">
                <div class="resources-card-media">
                    @if ($hasResourcesImage)
                        <img
                            src="{{ asset($resourcesImagePath) }}"
                            alt="Overhead view of people reviewing service directory information at a meeting table"
                        >
                    @else
                        <div class="resources-image-placeholder" role="img" aria-label="Service directory resource image placeholder">
                            <span>Service directory resource image</span>
                        </div>
                    @endif
                </div>

                <div
                    class="resources-card-body"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="body"
                >
                    <span class="section-kicker">Service Directories</span>
                    {!! $resourcesBodyForDisplay !!}
                </div>
            </div>

            @if (!empty($page->updated_at))
                <div class="page-updated-meta">
                    <span>Last updated:</span>
                    <time datetime="{{ $page->updated_at->format('Y-m-d') }}">
                        {{ $page->updated_at->format('F j, Y') }}
                    </time>
                </div>
            @endif

        </div>
    </section>

    @include('partials.page-cta')
@else

<section @class(['service-detail-hero', 'patient-portals-page-hero' => $isPatientPortals])>
    <div class="container service-detail-grid">
        <div class="service-detail-copy">
            <div class="service-breadcrumb">
                @if ($page->slug === 'healthline')
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Services</a>
                    <span>/</span>
                    <span>thehealthline.ca</span>
                @else
                    <a href="{{ route('home') }}">Home</a>
                    <span>/</span>
                    <span>Products &amp; Services</span>
                    <span>/</span>
                    <span>{{ $page->title }}</span>
                @endif
            </div>

            <span class="section-kicker">Products &amp; Services</span>

            <h1
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="title"
            >{{ $page->slug === 'healthline' ? 'Trusted Health & Community Service Navigation' : ($page->title ?: 'Trusted Health & Community Service Navigation') }}</h1>

            <p
                class="service-detail-excerpt"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="excerpt"
            >{{ $page->slug === 'healthline' ? 'Helping patients, caregivers, providers, and community partners find reliable service information across Ontario.' : ($page->excerpt ?: 'Helping patients, caregivers, providers, and community partners find reliable service information across Ontario.') }}</p>

            <div class="service-detail-actions">
                <a href="{{ route('search') }}" class="service-btn service-btn-light">Find Services</a>
                <a href="{{ route('contact') }}" class="service-btn service-btn-outline">Contact THLIN</a>
            </div>

            <div class="service-hero-tags">
                <span>Verified Information</span>
                <span>Ontario-Wide Directory</span>
                <span>Patient &amp; Provider Support</span>
            </div>
        </div>

        <aside class="service-hero-card" data-animate="fade-up">
            <h2>How thehealthline.ca Helps</h2>
            <ul class="service-highlight-list">
                <li>
                    <strong>Verified Service Listings</strong>
                    <span>Regularly updated health and community service information.</span>
                </li>
                <li>
                    <strong>Easier System Navigation</strong>
                    <span>Helping users quickly find the right care and support.</span>
                </li>
                <li>
                    <strong>Built for Ontario Partners</strong>
                    <span>Supporting patients, caregivers, providers, and organizations.</span>
                </li>
            </ul>
        </aside>
    </div>
</section>

@if ($isPatientPortals)
    <section class="service-detail-content patient-portals-page">
        <div class="service-detail-container">
            <article class="service-detail-card" data-animate="fade-up">
                <div
                    class="service-rich-content"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="body"
                >
                    {!! $patientBodyForDisplay !!}
                </div>

                <section class="patient-solutions-card">
                    <div class="patient-solutions-copy">
                        <span class="section-kicker">Solutions in Action</span>
                        <h2>See our solutions in action</h2>
                        <p>
                            Explore examples of patient-focused tools and service navigation websites supported by THLIN.
                        </p>
                    </div>

                    <div class="patient-solutions-links">
                        @foreach ($patientSolutionsLinks as $solutionLink)
                            <a href="{{ $solutionLink['url'] }}" class="patient-solution-btn" target="_blank" rel="noopener">
                                {{ $solutionLink['label'] }}
                            </a>
                        @endforeach
                    </div>
                </section>

                @if (!empty($page->updated_at))
                    <div class="page-meta-row">
                        <span class="page-last-updated">
                            Last updated: {{ $page->updated_at->format('F j, Y') }}
                        </span>
                    </div>
                @endif
            </article>
        </div>
    </section>
@elseif ($isSupportTraining)
    <section class="service-detail-content support-training-page">
        <div class="service-detail-container">
            <article class="service-detail-card" data-animate="fade-up">
                <div
                    class="service-rich-content"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="body"
                >
                    {!! $supportBodyForDisplay !!}
                </div>

                @if (!empty($page->updated_at))
                    <div class="page-meta-row">
                        <span class="page-last-updated">
                            Last updated: {{ $page->updated_at->format('F j, Y') }}
                        </span>
                    </div>
                @endif
            </article>
        </div>
    </section>
@else
    <section class="service-detail-content">
        <div class="container service-content-grid">
            <article class="service-main-card service-pro-card product-main-content-card" data-animate="fade-up">
                <div
                    class="service-rich-content"
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="body"
                >
                    {!! $page->body !!}
                </div>

                @if (!empty($page->updated_at))
                    <div class="page-meta-row">
                        <span class="page-last-updated">
                            Last updated: {{ $page->updated_at->format('F j, Y') }}
                        </span>
                    </div>
                @endif
            </article>

            <aside class="service-side-card service-pro-sidebar" data-animate="fade-up">
                <span class="sidebar-label">Explore</span>
                <h2>Explore Services</h2>
                <nav class="service-sidebar-nav" aria-label="Explore services">
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">
                        <span>thehealthline.ca</span>
                        <strong>→</strong>
                    </a>

                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">
                        <span>Patient Portals</span>
                        <strong>→</strong>
                    </a>

                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">
                        <span>Provider Portals</span>
                        <strong>→</strong>
                    </a>

                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">
                        <span>Support &amp; Training</span>
                        <strong>→</strong>
                    </a>

                    <a href="{{ route('contact') }}">
                        <span>Contact THLIN</span>
                        <strong>→</strong>
                    </a>
                </nav>
            </aside>
        </div>
    </section>
@endif

@if ($page->section === 'products' && !$isResources)
    <section class="service-feature-cards-section full-width-section" aria-label="Service Features">
        <div class="container">
            <div class="service-feature-heading">
                <span class="section-kicker blue">Service Features</span>
                <h2>Built to make service information easier to find and use</h2>
                <p>
                    thehealthline.ca helps people, caregivers, providers, and community partners access trusted
                    health and community service information across Ontario.
                </p>
            </div>

            <div class="service-feature-cards">
                <article class="service-feature-card" data-animate="fade-up">
                    <span class="feature-number">01</span>
                    <h3>Trusted Service Directory</h3>
                    <p>Search detailed health and community service listings across Ontario with clear, reliable information.</p>
                </article>

                <article class="service-feature-card" data-animate="fade-up">
                    <span class="feature-number">02</span>
                    <h3>Accurate Information</h3>
                    <p>Service details are reviewed, organized, and refreshed to help users find up-to-date information.</p>
                </article>

                <article class="service-feature-card" data-animate="fade-up">
                    <span class="feature-number">03</span>
                    <h3>Easy Navigation</h3>
                    <p>Designed to help patients, caregivers, and providers quickly find the right services and support.</p>
                </article>

                <article class="service-feature-card" data-animate="fade-up">
                    <span class="feature-number">04</span>
                    <h3>Connected Care Support</h3>
                    <p>Helps community partners and health system teams connect people to relevant local resources.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="why-matters-section full-width-section">
        <div class="container">
            <div class="why-matters-header">
                <span class="section-kicker blue">Why It Matters</span>
                <h2>Clear information helps people access the right support</h2>
                <p>
                    Patients, providers, and communities need trusted service information to make better decisions
                    and connect people with the right care.
                </p>
            </div>

            <div class="why-matters-grid">
                <article class="why-matters-card" data-animate="fade-up">
                    <span class="why-number">01</span>
                    <h3>Patients</h3>
                    <p>Patients need clear, simple information to understand available health and community services.</p>
                </article>

                <article class="why-matters-card" data-animate="fade-up">
                    <span class="why-number">02</span>
                    <h3>Providers</h3>
                    <p>Providers need accurate service data to guide referrals, care coordination, and system navigation.</p>
                </article>

                <article class="why-matters-card" data-animate="fade-up">
                    <span class="why-number">03</span>
                    <h3>Communities</h3>
                    <p>Communities need connected digital tools that make local support easier to find and access.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="related-services-section related-pages-section full-width-section" aria-label="Related Services">
        <div class="container">
            <div class="related-services-header related-pages-header">
                <span class="section-kicker blue">Related Services</span>
                <h2>Explore more THLIN services</h2>
                <p>Learn more about THLIN tools and support for patients, providers, and community partners.</p>
            </div>

            <div class="related-services-grid related-pages-grid">
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}" class="related-service-card related-page-card" data-animate="fade-up">
                    <span>01</span>
                    <h3>Patient Portals</h3>
                    <p>Digital tools that help patients and caregivers access clear service information.</p>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}" class="related-service-card related-page-card" data-animate="fade-up">
                    <span>02</span>
                    <h3>Provider Portals</h3>
                    <p>Support tools designed for providers, care teams, and system partners.</p>
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}" class="related-service-card related-page-card" data-animate="fade-up">
                    <span>03</span>
                    <h3>Support &amp; Training</h3>
                    <p>Resources and support to help teams use THLIN tools effectively.</p>
                </a>
            </div>
        </div>
    </section>
@endif

@include('partials.page-cta')

@endif

@else

<section class="page-content-section">
    <div class="container page-content-card">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>

        @if ($page->excerpt)
            <p
                class="page-excerpt"
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="excerpt"
            >{{ $page->excerpt }}</p>
        @endif

        <div
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >
            {!! $page->body !!}
        </div>

        @if (!empty($page->updated_at))
            <div class="page-meta-row">
                <span class="page-last-updated">
                    Last updated: {{ $page->updated_at->format('F j, Y') }}
                </span>
            </div>
        @endif
    </div>
</section>

@includeIf('partials.page-cta')

@endif

@endsection