@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])

@section('content')
    @include('partials.page-header', ['page' => $page])

    <section class="page-content">
        <div class="container prose">
            {!! $page->body !!}
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="section section-alt">
            <div class="container">
                <h2>Highlighted Projects</h2>
                <div class="card-grid">
                    @foreach ($featured as $item)
                        <a href="{{ $item->url }}" class="card" target="_blank" rel="noopener">
                            <h3>{{ $item->title }}</h3>
                            <p>{{ $item->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($past->isNotEmpty())
        <section class="section">
            <div class="container">
                <h2>Past Projects</h2>
                <ul class="project-list">
                    @foreach ($past as $item)
                        <li>
                            <a href="{{ $item->url }}" target="_blank" rel="noopener"><strong>{{ $item->title }}</strong></a>
                            <p>{{ $item->excerpt }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    <section class="section section-alt">
        <div class="container">
            <h2>Interested in Collaborating?</h2>
            <p>We work with partners to improve information systems and connect the people of Ontario to relevant health and community services.</p>
            <p><a href="mailto:{{ $thlin['contact_email'] }}">{{ $thlin['contact_email'] }}</a></p>
        </div>
    </section>
@endsection
