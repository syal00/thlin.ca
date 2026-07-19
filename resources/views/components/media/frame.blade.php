@props([
    'src' => null,
    'alt' => '',
    'ratio' => '4:3',
    'objectPosition' => 'center',
    'loading' => 'lazy',
    'decorative' => false,
    'placeholder' => 'Image coming soon',
    'caption' => null,
    'width' => null,
    'height' => null,
    // Inline-edit-image passthrough (see partials/inline-edit-attrs.blade.php
    // for the text/richtext equivalent — image fields use this separate,
    // hand-rolled attribute set per InlineEditController::IMAGE_FIELDS).
    'editableImage' => false,
    'editModel' => null,
    'editId' => null,
    'editField' => null,
])

@php
    $ratioClass = str_replace([':', '.'], ['-', ''], strtolower($ratio));
    $ratioClass = match ($ratioClass) {
        '1-1' => 'square',
        '3-4' => 'portrait',
        default => $ratioClass,
    };
    $hasImage = ! empty($src);
    $altText = $decorative ? '' : $alt;
@endphp

<figure {{ $attributes->merge(['class' => "media-frame-wrap"]) }}>
    @if ($hasImage)
        <div class="media-frame media-frame--{{ $ratioClass }}">
            <img
                src="{{ $src }}"
                alt="{{ $altText }}"
                loading="{{ $loading }}"
                decoding="async"
                style="object-position: {{ $objectPosition }};"
                @if ($width) width="{{ $width }}" @endif
                @if ($height) height="{{ $height }}" @endif
                @if ($editableImage && $editModel && $editId && $editField)
                    data-editable-image="true"
                    data-model="{{ $editModel }}"
                    data-id="{{ $editId }}"
                    data-field="{{ $editField }}"
                @endif
            >
        </div>
    @else
        <div
            class="media-placeholder media-placeholder--{{ $ratioClass }}"
            @if ($decorative)
                aria-hidden="true"
            @else
                role="img"
                aria-label="{{ $placeholder }}"
            @endif
            @if ($editableImage && $editModel && $editId && $editField)
                data-editable-image="true"
                data-model="{{ $editModel }}"
                data-id="{{ $editId }}"
                data-field="{{ $editField }}"
            @endif
        >
            <span class="media-placeholder__icon" aria-hidden="true"></span>
            @if (! $decorative)
                <span class="media-placeholder__label">{{ $placeholder }}</span>
            @endif
        </div>
    @endif

    @if ($caption)
        <figcaption class="media-frame-caption">{{ $caption }}</figcaption>
    @endif
</figure>
