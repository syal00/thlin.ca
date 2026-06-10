@extends('layouts.app')

@section('title', 'Search'.($query ? ': '.$query : '').' - '.$thlin['name'])

@section('content')
    <div class="page-title light-background">
        <div class="breadcrumbs">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                    <li class="breadcrumb-item active current">Search</li>
                </ol>
            </nav>
        </div>
        <div class="title-wrapper">
            <h1>Search</h1>
            <form class="thlin-search-form mx-auto" action="{{ route('search') }}" method="get" role="search" style="max-width: 520px;">
                <div class="input-group">
                    <input type="search" name="q" class="form-control" value="{{ $query }}" placeholder="Search the site" aria-label="Search query">
                    <button type="submit" class="btn btn-solid">Search</button>
                </div>
            </form>
        </div>
    </div>

    <section class="section">
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
