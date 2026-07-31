@php
    $html = $html ?? '';
    $hasBody = trim(strip_tags($html)) !== '';
@endphp

@if ($hasBody || auth()->check())
    @auth
        <div class="t-prose-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
            @include('partials.cms-body', ['html' => $html])
        </div>
    @else
        <div class="t-prose-content">@include('partials.cms-body', ['html' => $html])</div>
    @endauth
@endif
