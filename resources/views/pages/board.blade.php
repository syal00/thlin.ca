@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('hero')
    @include('partials.page-header', ['page' => $page, 'eyebrow' => 'Leadership'])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @if ($page->body)
                    <div class="cms-content">{!! $page->body !!}</div>
                @endif

                <div class="board-grid">
                    @foreach ($members as $member)
                        <article class="board-card service-card">
                            @if ($member->photoUrl())
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="board-photo">
                            @endif
                            <h2>{{ $member->name }}</h2>
                            <p class="board-role">{{ $member->role }}</p>
                            <p>{{ $member->bio }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
