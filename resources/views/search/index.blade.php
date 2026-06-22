<section class="search-page-section">
    <div class="container">
        <div class="search-main-card">
            <div class="search-page-header">
                <span class="section-kicker">Search THLIN</span>

                <h1>Find services, pages, and resources</h1>

                <p>
                    Search across THLIN pages, services, portals, resources, and support information.
                </p>
            </div>

            <form method="GET" action="{{ route('search') }}" class="professional-search-form">
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

            <div class="search-suggestion-panel">
                <span>Suggested searches:</span>
                <a href="{{ route('search', ['q' => 'patient portals']) }}">Patient Portals</a>
                <a href="{{ route('search', ['q' => 'provider portals']) }}">Provider Portals</a>
                <a href="{{ route('search', ['q' => 'support training']) }}">Support &amp; Training</a>
                <a href="{{ route('search', ['q' => 'annual reports']) }}">Annual Reports</a>
                <a href="{{ route('search', ['q' => 'contact']) }}">Contact</a>
            </div>

            @if (request('q'))
                <div class="search-results">
                    @if ($results->isEmpty())
                        <div class="search-empty-state">
                            <span class="empty-state-icon">⌕</span>

                            <div>
                                <h2>No results found</h2>
                                <p>Try searching with a different keyword or browse the suggested links above.</p>
                            </div>
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
            @else
                <div class="search-empty-state">
                    <span class="empty-state-icon">⌕</span>

                    <div>
                        <h2>Start your search</h2>
                        <p>Enter a keyword above to find pages, services, and resources across THLIN.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="guided-service-section">
    <div class="container">
        <div class="guided-service-card">
            <div class="guided-service-header">
                <span class="section-kicker">Guided Service Finder</span>

                <h2>Not sure where to start?</h2>

                <p>Use these quick options to find the right THLIN resource.</p>
            </div>

            <div class="guided-service-grid">
                <article class="guided-service-option">
                    <span class="guided-number">01</span>
                    <h3>Find Health Services</h3>
                    <p>Search THLIN pages, services, and resources in one place.</p>
                    <a href="{{ route('search', ['q' => 'health services']) }}">Open Search</a>
                </article>

                <article class="guided-service-option">
                    <span class="guided-number">02</span>
                    <h3>Patient Portals</h3>
                    <p>Explore tools designed for patients and caregivers.</p>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Open Patient Portals</a>
                </article>

                <article class="guided-service-option">
                    <span class="guided-number">03</span>
                    <h3>Provider Portals</h3>
                    <p>Find provider-focused tools and support resources.</p>
                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Open Provider Portals</a>
                </article>

                <article class="guided-service-option">
                    <span class="guided-number">04</span>
                    <h3>Contact THLIN</h3>
                    <p>Reach our team for guidance on services and navigation.</p>
                    <a href="{{ route('contact') }}">Contact THLIN</a>
                </article>
            </div>
        </div>
    </div>
</section>
