@extends('layouts.app')

@section('title', $post->title.' - '.$thlin['name'])

@section('content')
    <div class="page-title light-background">
        <div class="breadcrumbs">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">News</a></li>
                    <li class="breadcrumb-item active current">{{ $post->title }}</li>
                </ol>
            </nav>
        </div>
        <div class="title-wrapper">
            <h1>{{ $post->title }}</h1>
            @if ($post->published_at)
                <p>{{ $post->published_at->format('F j, Y') }}@if ($post->location) &middot; {{ $post->location }}@endif</p>
            @endif
        </div>
    </div>

    <section class="section">
        <div class="container prose">
            {!! $post->body !!}
        </div>
        <div class="container mt-4">
            <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}" class="link-action"><i class="bi bi-arrow-left"></i> Back to News</a>
        </div>
    </section>
@endsection
