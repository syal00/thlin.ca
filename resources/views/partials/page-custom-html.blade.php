@php
    $html = trim($html ?? '');
@endphp

@if ($html !== '')
    <div class="custom-html-content">
        {!! $html !!}
    </div>
@endif
