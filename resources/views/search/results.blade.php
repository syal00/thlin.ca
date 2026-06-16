@extends('layouts.app')

@section('title', 'Search'.($query ? ': '.$query : '').' - '.$thlin['name'])

@section('hero')
    @include('partials.page-header', [
        'editable' => false,
        'eyebrow' => 'THLIN',
        'heroTitle' => 'Search',
        'heroSubtitle' => 'Find pages, tools, news, and resources across the THLIN website.',
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Search', 'current' => true],
        ],
        'hideDefaultActions' => true,
    ])
@endsection

@section('content')
    <section class="home-section section-light">
        <div class="section-container">
            <div class="content-shell">
                @if ($query === '')
                    <p>Enter a search term to find pages across the site.</p>
                @elseif ($results->isEmpty())
                    <p>No results found for &ldquo;{{ $query }}&rdquo;.</p>
                @else
                    <p>{{ $results->count() }} result(s) for &ldquo;{{ $query }}&rdquo;</p>
                    <ul class="search-results">
                        @foreach ($results as $result)
                            <li>
                                <span class="result-type">{{ $result['type'] }}</span>
                                <h2><a href="{{ $result['url'] }}">{{ $result['title'] }}</a></h2>
                                @if (! empty($result['excerpt']))
                                    <p>{{ $result['excerpt'] }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </section>
@endsection
