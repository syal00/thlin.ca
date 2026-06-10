@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="section">
        <div class="container prose mb-5">
            {!! $page->body !!}
        </div>
        <div class="container">
            <div class="row gy-4">
                @foreach ($members as $member)
                    <div class="col-lg-6">
                        <article class="board-card h-100">
                            <h2>{{ $member->name }}</h2>
                            <p class="board-role">{{ $member->role }}</p>
                            <p>{{ $member->bio }}</p>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
