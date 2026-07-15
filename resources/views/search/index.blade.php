<section class="t-prose">
    <div class="t-container">
        <form method="GET" action="{{ route('search') }}" class="t-search-form">
            <label for="q" class="t-visually-hidden">Search</label>
            <input
                id="q"
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search services, pages, resources..."
            >
            <button type="submit" class="t-btn t-btn-primary">Search</button>
        </form>

        <div class="t-search-suggestions">
            <span>Suggested searches:</span>
            <a href="{{ route('search', ['q' => 'patient portals']) }}">Patient Portals</a>
            <a href="{{ route('search', ['q' => 'provider portals']) }}">Provider Portals</a>
            <a href="{{ route('search', ['q' => 'support training']) }}">Support &amp; Training</a>
            <a href="{{ route('search', ['q' => 'annual reports']) }}">Annual Reports</a>
            <a href="{{ route('search', ['q' => 'contact']) }}">Contact</a>
        </div>

        @if (request('q'))
            @if ($results->isEmpty())
                <div class="t-search-empty">
                    <h2>No results found</h2>
                    <p>Try searching with a different keyword or browse the suggested links above.</p>
                </div>
            @else
                <h2>Search Results</h2>

                <ul class="t-search-results">
                    @foreach ($results as $result)
                        @include('partials.search-result-card', ['result' => $result])
                    @endforeach
                </ul>
            @endif
        @else
            <div class="t-search-empty">
                <h2>Start your search</h2>
                <p>Enter a keyword above to find pages, services, and resources across THLIN.</p>
            </div>
        @endif
    </div>
</section>

<section class="t-prose t-prose--alt">
    <div class="t-container">
        <div class="t-section-head">
            <span class="t-eyebrow">Guided Service Finder</span>
            <h2>Not sure where to start?</h2>
            <p>Use these quick options to find the right THLIN resource.</p>
        </div>

        <div class="t-guided-grid">
            <article class="t-guided-card">
                <span class="t-guided-number">01</span>
                <h3>Find Health Services</h3>
                <p>Search THLIN pages, services, and resources in one place.</p>
                <a href="{{ route('search', ['q' => 'health services']) }}">Open Search</a>
            </article>

            <article class="t-guided-card">
                <span class="t-guided-number">02</span>
                <h3>Patient Portals</h3>
                <p>Explore tools designed for patients and caregivers.</p>
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Open Patient Portals</a>
            </article>

            <article class="t-guided-card">
                <span class="t-guided-number">03</span>
                <h3>Provider Portals</h3>
                <p>Find provider-focused tools and support resources.</p>
                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Open Provider Portals</a>
            </article>

            <article class="t-guided-card">
                <span class="t-guided-number">04</span>
                <h3>Contact THLIN</h3>
                <p>Reach our team for guidance on services and navigation.</p>
                <a href="{{ route('contact') }}">Contact THLIN</a>
            </article>
        </div>
    </div>
</section>
