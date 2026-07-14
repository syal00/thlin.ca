@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' - '.$thlin['name'])

@if (! empty($page->meta_keywords))
    @push('head')
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endpush
@endif

@section('hero')
    <section class="simple-hero">
        <div class="container simple-hero-inner">
            <h1>{{ $page->title }}</h1>
        </div>
    </section>
@endsection

@section('content')
    {{-- Matches thlin.ca's Board of Directors page: a single stacked column of
         member photo + name/role + bio, no card grid, no boxes. The page
         body field is intentionally not rendered here — it duplicated this
         same member list (imported legacy HTML), so the loop below is the
         single source of truth. If the body field still holds that legacy
         content, clear it via /admin → Pages → Board of Directors. --}}
    <section class="simple-section simple-section--light">
        <div class="container simple-prose">
            @include('partials.page-updated', ['page' => $page])

            @foreach ($members as $member)
                <div class="board-member">
                    @if ($member->photoUrl())
                        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="board-photo">
                    @endif
                    <h3 @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'name', 'type' => 'text'])>{{ $member->name }} - {{ $member->role }}</h3>
                    <p @include('partials.inline-edit-attrs', ['model' => 'board', 'id' => $member->id, 'field' => 'bio', 'type' => 'richtext'])>{!! $member->bio !!}</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
