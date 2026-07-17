@props([
    'href',
    'label',
])

@php
    // Derives the real YouTube thumbnail for the exact linked video (YouTube's
    // own CDN, not a random/stock image) rather than a generic placeholder —
    // this is accurate content, not decoration.
    $videoId = null;

    if (preg_match('/(?:youtu\.be\/|v=|\/embed\/)([a-zA-Z0-9_-]{11})/', $href, $matches)) {
        $videoId = $matches[1];
    }

    $thumbnailUrl = $videoId ? "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg" : null;
@endphp

<a href="{{ $href }}" target="_blank" rel="noopener" class="media-video-thumb" aria-label="{{ $label }} (opens on YouTube)">
    <span class="media-frame media-frame--16-9">
        @if ($thumbnailUrl)
            <img src="{{ $thumbnailUrl }}" alt="" loading="lazy" decoding="async">
        @endif
        <span class="media-video-thumb__play" aria-hidden="true">&#9658;</span>
    </span>
    <span class="media-video-thumb__label">{{ $label }}</span>
</a>
