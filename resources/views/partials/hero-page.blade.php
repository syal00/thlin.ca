@php
    $heroRecord = $record ?? ($page ?? null);
    $heroTitle = $heroTitle ?? ($heroRecord->hero_title ?? $heroRecord->title ?? 'Page');
    $heroSubtitle = $heroSubtitle ?? null;
    $eyebrowLabel = $eyebrow ?? 'THLIN';
    $isEditable = $heroRecord && ($editable ?? true);
    $editModel = $model ?? 'page';
    $titleFieldName = $titleField ?? 'title';

    $sectionLabels = [
        'products' => 'Products & Services',
        'partners' => 'Partners',
        'about' => 'About',
    ];
    $sectionLandingSlugs = [
        'products' => 'products-services',
        'partners' => 'partners',
        'about' => 'about',
    ];

    if (! isset($breadcrumbs) && $heroRecord instanceof \App\Models\Page) {
        $breadcrumbs = [['label' => 'Home', 'url' => route('home')]];

        if ($heroRecord->parent) {
            $breadcrumbs[] = ['label' => $heroRecord->parent->menu_label, 'url' => url($heroRecord->parent->full_url)];
        } elseif ($heroRecord->section && ! in_array($heroRecord->section, ['home', 'contact'], true)) {
            $landingSlug = $sectionLandingSlugs[$heroRecord->section] ?? $heroRecord->section;
            $label = $sectionLabels[$heroRecord->section] ?? ucfirst($heroRecord->section);

            if ($heroRecord->slug !== $landingSlug) {
                $breadcrumbs[] = ['label' => $label, 'url' => url('/'.$landingSlug)];
            }
        }

        $breadcrumbs[] = ['label' => $heroTitle, 'current' => true];
    }

    $breadcrumbs = $breadcrumbs ?? null;
@endphp

<section class="t-hero-page t-hero-page--image @if(!empty($heroImageVariant)) t-hero-page--{{ $heroImageVariant }} @endif" @if(!empty($heroImage)) style="background-image: url('{{ $heroImage }}')" @endif>
    <div class="t-container t-hero-page-inner">
        @include('partials.breadcrumb', ['breadcrumbs' => $breadcrumbs])

        <span class="t-eyebrow t-eyebrow--on-dark">{{ $eyebrowLabel }}</span>

        @if ($isEditable)
            <h1 @include('partials.inline-edit-attrs', ['model' => $editModel, 'id' => $heroRecord->id, 'field' => $titleFieldName, 'type' => 'text'])>{{ $heroTitle }}</h1>
        @else
            <h1>{{ $heroTitle }}</h1>
        @endif

        @if ($heroSubtitle)
            <p>{{ $heroSubtitle }}</p>
        @endif
    </div>
</section>
