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
    <section class="board-page">
        <div class="board-container">
            <article class="board-card">
                <div class="board-content">
                    <div
                        data-editable="true"
                        data-model="page"
                        data-id="{{ $page->id }}"
                        data-field="body"
                    >
                        {!! $page->body !!}
                    </div>

                    <div class="t-card-grid board-members-grid">
                        @foreach ($members as $member)
                            @include('partials.card-board', ['member' => $member])
                        @endforeach
                    </div>
                </div>

                @if (!empty($page->updated_at))
                    <div class="page-updated-meta">
                        <span>Last updated:</span>
                        <time datetime="{{ $page->updated_at->format('Y-m-d') }}">
                            {{ $page->updated_at->format('F j, Y') }}
                        </time>
                    </div>
                @endif
            </article>
        </div>
    </section>
@endsection
