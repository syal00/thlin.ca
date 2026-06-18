@extends('layouts.app')

@section('title', 'Search - '.$thlin['name'])
@section('meta_description', 'Search pages, tools, services, and resources across the THLIN website.')

@section('content')

<section class="search-hero">
    <div class="container">
        <div class="search-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span>Search</span>
        </div>

        <span class="section-kicker">THLIN</span>

        <h1>Search THLIN Resources</h1>

        <p>
            Find pages, tools, services, and resources across the THLIN website.
        </p>
    </div>
</section>

<section class="search-page-section">
    <div class="container">
        <div class="search-card">
            <div class="search-card-header">
                <h2>What are you looking for?</h2>
                <p>Enter a keyword to search across THLIN pages, services, news, and resources.</p>
            </div>

            <form method="GET" action="{{ route('search') }}" class="search-form">
                <label for="q" class="sr-only">Search</label>

                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search services, pages, resources..."
                >

                <button type="submit">Search</button>
            </form>

            <div class="search-suggestions">
                <span>Suggested:</span>
                <a href="{{ route('search', ['q' => 'products services']) }}">Products &amp; Services</a>
                <a href="{{ route('search', ['q' => 'patient portals']) }}">Patient Portals</a>
                <a href="{{ route('search', ['q' => 'contact']) }}">Contact</a>
                <a href="{{ route('search', ['q' => 'annual reports']) }}">Annual Reports</a>
            </div>

            <div class="search-results">
                @if ($query === '')
                    <div class="search-empty">
                        <h2>Start your search</h2>
                        <p>Enter a keyword above to find pages, services, and resources across THLIN.</p>
                    </div>
                @elseif ($results->isEmpty())
                    <div class="search-empty">
                        <h2>No results found</h2>
                        <p>Try searching with a different keyword or browse the suggested links above.</p>
                    </div>
                @else
                    <h2>Search Results</h2>

                    <div class="search-result-list">
                        @foreach ($results as $result)
                            <article class="search-result-item">
                                <h3>
                                    <a href="{{ $result['url'] ?? '#' }}">
                                        {{ $result['title'] ?? 'Untitled' }}
                                    </a>
                                </h3>

                                @if (!empty($result['excerpt']))
                                    <p>{{ $result['excerpt'] }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
