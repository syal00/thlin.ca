@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('content')

@if ($page->section === 'about' && $page->slug === 'us')

<section class="simple-hero">
    <div class="container simple-hero-inner">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>
    </div>
</section>

<section class="simple-section simple-section--light">
    <div class="container simple-prose">
        <div
            class="about-rich-content"
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="body"
        >
            {!! \App\Support\AboutBodyFormatter::render($page->body) !!}
        </div>

        @if ($page->updated_at)
            <p class="simple-updated">Last updated: {{ $page->updated_at->format('F j, Y') }}</p>
        @endif
    </div>
</section>

@elseif (in_array($page->section, ['products', 'partners'], true))

@include('partials.capability-page', ['page' => $page, 'section' => $page->section])

@else

<section class="simple-hero">
    <div class="container simple-hero-inner">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>
    </div>
</section>

<section class="simple-section simple-section--light">
    <div class="container simple-prose">
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

        @if ($page->updated_at)
            <p class="simple-updated">Last updated: {{ $page->updated_at->format('F j, Y') }}</p>
        @endif
    </div>
</section>

@endif

@endsection
