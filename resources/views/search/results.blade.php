@extends('layouts.app')

@section('title', 'Search'.($query ? ': '.$query : '').' - '.$thlin['name'])

@section('content')
    <div class="page-header">
        <div class="container">
            <h1>Search</h1>
            <form class="search-bar" action="{{ route('search') }}" method="get" role="search" style="margin: 1.5rem auto 0; max-width: 520px;">
                <input type="search" name="q" value="{{ $query }}" placeholder="Search the site" aria-label="Search query">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <section class="page-content">
        <div class="container">
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
    </section>
@endsection
