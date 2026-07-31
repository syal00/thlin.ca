@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@php
    $heroImageVariant = match (true) {
        $page->section === 'about' && $page->slug === 'us' => 'about',
        default => null,
    };
@endphp

@section('hero')
    @include('partials.hero-page', ['page' => $page, 'heroImageVariant' => $heroImageVariant])
@endsection

@section('content')

@if ($page->section === 'about' && $page->slug === 'us')

    <section class="t-prose">
        <div class="t-container">
            @include('partials.page-custom-html', ['html' => $page->custom_html])
            <div class="t-prose-content about-rich-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                {!! \App\Support\AboutBodyFormatter::render($page->body) !!}
            </div>

            @include('partials.page-updated', ['page' => $page])
        </div>
    </section>

@elseif (in_array($page->section, ['products', 'partners'], true))

    @include('partials.page-custom-html', ['html' => $page->custom_html])
    @include('partials.capability-page', ['page' => $page, 'section' => $page->section])

@else

    <section class="t-prose">
        <div class="t-container">
            @include('partials.page-custom-html', ['html' => $page->custom_html])
            @if ($page->excerpt)
                <p class="page-excerpt" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'excerpt', 'type' => 'text'])>{{ $page->excerpt }}</p>
            @endif

            <div class="t-prose-content" @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'body', 'type' => 'richtext'])>
                {!! $page->body !!}
            </div>

            @include('partials.page-updated', ['page' => $page])
        </div>
    </section>

@endif

@endsection
