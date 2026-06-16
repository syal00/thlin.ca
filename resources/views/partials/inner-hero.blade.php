@php
    $heroTitle = $heroTitle ?? ($page->hero_title ?? $page->title ?? 'Page');
    $heroSubtitle = $heroSubtitle ?? ($page->hero_subtitle ?? $page->excerpt ?? ($page->meta_description ?? null));
    $eyebrowLabel = $eyebrow ?? 'THLIN';

    $sectionLabels = [
        'products' => 'Products & Services',
        'partners' => 'Partners',
        'about' => 'About',
        'contact' => 'Contact',
    ];

    $sectionLandingSlugs = [
        'products' => 'products-services',
        'partners' => 'partners',
        'about' => 'about',
    ];

    if (empty($breadcrumbs)) {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
        ];

        if (isset($page) && $page->parent) {
            $breadcrumbs[] = ['label' => $page->parent->title, 'url' => $page->parent->url()];
        } elseif (isset($page) && $page->section && ! in_array($page->section, ['home', 'contact'], true)) {
            $landingSlug = $sectionLandingSlugs[$page->section] ?? $page->section;
            $label = $sectionLabels[$page->section] ?? ucfirst($page->section);

            if ($page->slug !== $landingSlug) {
                $breadcrumbs[] = ['label' => $label, 'url' => url('/'.$landingSlug)];
            }
        }

        $breadcrumbs[] = ['label' => $heroTitle, 'current' => true];
    }

    if (isset($page) && ($editable ?? true)) {
        $resolvedTitleField = $titleField ?? ($page->hero_title ? 'hero_title' : 'title');
        $resolvedSubtitleField = $subtitleField ?? ($page->hero_subtitle ? 'hero_subtitle' : 'excerpt');
    }
@endphp

<section class="inner-hero">
    <div class="inner-hero-bg" aria-hidden="true"></div>

    <div class="inner-container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            @foreach ($breadcrumbs as $crumb)
                @if (! empty($crumb['url']) && empty($crumb['current']))
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @else
                    <span @if (! empty($crumb['current'])) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                @endif

                @if (! $loop->last)
                    <span class="breadcrumb-sep" aria-hidden="true">/</span>
                @endif
            @endforeach
        </nav>

        <div class="inner-hero-content">
            <span class="section-eyebrow">{{ $eyebrowLabel }}</span>

            @if (isset($page) && ($editable ?? true))
                <h1
                    data-editable="true"
                    data-model="page"
                    data-id="{{ $page->id }}"
                    data-field="{{ $resolvedTitleField ?? ($titleField ?? 'title') }}"
                >{{ $heroTitle }}</h1>

                @if ($heroSubtitle)
                    <p
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="{{ $resolvedSubtitleField ?? ($subtitleField ?? 'excerpt') }}"
                    >{{ $heroSubtitle }}</p>
                @endif
            @else
                <h1>{{ $heroTitle }}</h1>
                @if ($heroSubtitle)
                    <p>{{ $heroSubtitle }}</p>
                @endif
            @endif
        </div>
    </div>
</section>
