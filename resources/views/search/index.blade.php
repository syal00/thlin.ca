<section class="thlin-search-page">
    <div class="thlin-search-container">
        <div class="thlin-search-header">
            <div class="thlin-search-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <span>Search</span>
            </div>

            <span class="section-kicker">Search THLIN</span>
            <h1>Find THLIN resources</h1>
            <p>
                Search pages, services, tools, news, and resources across the THLIN website.
            </p>
        </div>

        <div class="thlin-search-panel">
            <form method="GET" action="{{ route('search') }}" class="thlin-search-form">
                <label for="q" class="sr-only">Search THLIN resources</label>

                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search services, pages, resources..."
                >

                <button type="submit">Search</button>
            </form>

            <div class="thlin-search-suggestions">
                <span>Popular links:</span>

                <a href="{{ route('search') }}">Find Health Services</a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">
                    Patient Portals
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">
                    Provider Portals
                </a>

                <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">
                    Support &amp; Training
                </a>

                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'annual-reports']) }}">
                    Annual Reports
                </a>

                <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'careers']) }}">
                    Careers
                </a>
            </div>
        </div>

        @if (request('q'))
            <div class="thlin-search-results">
                <div class="thlin-search-results-header">
                    <span class="section-kicker">Results</span>
                    <h2>Search results for "{{ request('q') }}"</h2>
                </div>

                @if ($results->isEmpty())
                    <article class="thlin-search-result-card">
                        <h3>No results found</h3>
                        <p>Try searching with a different keyword or browse the suggested searches above.</p>
                    </article>
                @else
                    @foreach ($results as $result)
                        <a class="thlin-search-result-card" href="{{ $result['url'] ?? '#' }}">
                            <h3>{{ $result['title'] ?? 'Untitled' }}</h3>

                            @if (!empty($result['excerpt']))
                                <p>{{ $result['excerpt'] }}</p>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>
        @else
            <div class="thlin-guided-search">
                <div class="thlin-guided-search-header">
                    <span class="section-kicker">Quick Access</span>
                    <h2>Not sure where to start?</h2>
                    <p>Use these quick options to find the right THLIN resource.</p>
                </div>

                <div class="thlin-guided-grid">
                    <a href="{{ route('search', ['q' => 'health services']) }}" class="thlin-guided-card">
                        <strong>Find Health Services</strong>
                        <span>Search THLIN pages, services, and resources in one place.</span>
                    </a>

                    <a href="{{ route('search', ['q' => 'Patient Portals']) }}" class="thlin-guided-card">
                        <strong>Patient Portals</strong>
                        <span>Explore tools designed for patients and caregivers.</span>
                    </a>

                    <a href="{{ route('search', ['q' => 'Provider Portals']) }}" class="thlin-guided-card">
                        <strong>Provider Portals</strong>
                        <span>Find provider-focused tools and support resources.</span>
                    </a>

                    <a href="{{ route('contact') }}" class="thlin-guided-card">
                        <strong>Contact THLIN</strong>
                        <span>Reach our team for support and guidance.</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
