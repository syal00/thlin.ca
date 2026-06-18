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
                <span>Suggested searches:</span>
                <a href="{{ route('search', ['q' => 'patient portals']) }}">Patient Portals</a>
                <a href="{{ route('search', ['q' => 'provider portals']) }}">Provider Portals</a>
                <a href="{{ route('search', ['q' => 'support training']) }}">Support &amp; Training</a>
                <a href="{{ route('search', ['q' => 'annual reports']) }}">Annual Reports</a>
                <a href="{{ route('search', ['q' => 'contact']) }}">Contact</a>
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
