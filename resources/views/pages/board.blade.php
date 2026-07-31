@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    @include('partials.hero-page', ['page' => $page])
@endsection

@section('content')
    <section class="t-prose">
        <div class="t-container">
            @include('partials.page-custom-html', ['html' => $page->custom_html])
            @include('partials.page-body-block', ['page' => $page, 'html' => $page->body])

            @include('partials.page-updated', ['page' => $page])

            <div class="t-card-grid">
                @foreach ($members as $member)
                    @include('partials.card-board', ['member' => $member])
                @endforeach
            </div>
        </div>
    </section>
@endsection
