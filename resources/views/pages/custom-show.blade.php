@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->meta_description ?: $page->hero_subtitle)

@section('content')
    <section class="page-header">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>

                @if ($page->parent)
                    <span aria-hidden="true">/</span>
                    <a href="{{ url('/'.$page->parent->slug) }}">{{ $page->parent->title }}</a>
                @endif

                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $page->title }}</span>
            </nav>

            <h1>{{ $page->hero_title ?: $page->title }}</h1>

            @if ($page->hero_subtitle)
                <p class="page-excerpt">{{ $page->hero_subtitle }}</p>
            @endif
        </div>
    </section>

    <section class="page-content">
        <div class="container">
            <article class="cms-content page-content-card">
                {!! $page->body !!}
            </article>
        </div>
    </section>
@endsection
