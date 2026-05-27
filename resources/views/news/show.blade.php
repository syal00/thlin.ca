@extends('layouts.app')

@section('title', $post->title.' - '.$thlin['name'])

@section('content')
    <div class="page-header">
        <div class="container">
            <h1>{{ $post->title }}</h1>
            @if ($post->published_at)
                <p class="page-lead">{{ $post->published_at->format('F j, Y') }}@if ($post->location) &middot; {{ $post->location }}@endif</p>
            @endif
        </div>
    </div>

    <section class="page-content">
        <div class="container prose">
            {!! $post->body !!}
        </div>
        <div class="container" style="margin-top: 2rem;">
            <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'news']) }}">&larr; Back to News</a>
        </div>
    </section>
@endsection
