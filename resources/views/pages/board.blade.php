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
    {{-- Matches thlin.ca's Board of Directors page structure: photo + name/role
         + bio per member. The page body field is intentionally not rendered
         here — it duplicated this same member list (imported legacy HTML), so
         the loop below is the single source of truth. If the body field
         still holds that legacy content, clear it via /admin -> Pages ->
         Board of Directors. --}}
    <section class="t-prose">
        <div class="t-container">
            @include('partials.page-updated', ['page' => $page])

            <div class="t-card-grid">
                @foreach ($members as $member)
                    @include('partials.card-board', ['member' => $member])
                @endforeach
            </div>
        </div>
    </section>
@endsection
