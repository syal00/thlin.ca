@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="page-content">
        <div class="container prose">
            {!! $page->body !!}
        </div>
        <div class="container board-grid">
            @foreach ($members as $member)
                <article class="board-card">
                    <h2>{{ $member->name }}</h2>
                    <p class="board-role">{{ $member->role }}</p>
                    <p>{{ $member->bio }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
