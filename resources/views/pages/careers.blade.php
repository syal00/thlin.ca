@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="section">
        <div class="container prose mb-5">
            {!! $page->body !!}
        </div>
        <div class="container">
            @foreach ($jobs as $job)
                <article class="job-card" id="{{ $job->slug }}">
                    <h2>{{ $job->title }}</h2>
                    <p class="job-meta">
                        @if ($job->location){{ $job->location }}@endif
                        @if ($job->employment_type) &middot; {{ $job->employment_type }}@endif
                    </p>
                    @if ($job->posted_at || $job->closes_at)
                        <p class="job-meta">
                            @if ($job->posted_at)Posted {{ $job->posted_at->format('F j, Y') }}@endif
                            @if ($job->closes_at) &middot; Closes {{ $job->closes_at->format('F j, Y') }}@endif
                        </p>
                    @endif
                    <div class="prose">{!! $job->body !!}</div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
