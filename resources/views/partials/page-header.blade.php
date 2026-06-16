@php
    $sectionEyebrows = [
        'about' => 'About THLIN',
        'products' => 'Products & Services',
        'partners' => 'Partners',
    ];

    $eyebrow = $eyebrow ?? (isset($page) ? ($sectionEyebrows[$page->section] ?? 'THLIN') : 'THLIN');
@endphp

@include('partials.page-hero', array_merge(
    ['page' => $page ?? null, 'eyebrow' => $eyebrow],
    array_filter([
        'titleField' => $titleField ?? null,
        'subtitleField' => $subtitleField ?? null,
        'heroTitle' => $heroTitle ?? null,
        'heroSubtitle' => $heroSubtitle ?? null,
        'breadcrumbs' => $breadcrumbs ?? null,
        'editable' => $editable ?? null,
        'hideDefaultActions' => $hideDefaultActions ?? null,
    ], fn ($value) => $value !== null)
))
