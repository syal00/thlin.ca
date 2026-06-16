<div class="hero-card">
    <div class="hero-card-icon" aria-hidden="true">+</div>
    <h2>Find trusted health and community services</h2>
    <p>Search information, tools, news, and partner resources across the THLIN network.</p>

    <ul>
        <li>Health and social service directories</li>
        <li>Easy-to-use system navigation tools</li>
        <li>Digital support for community partners</li>
    </ul>

    <form action="{{ route('search') }}" method="get" class="hero-search" role="search">
        <label for="{{ $searchInputId ?? 'hero-search-input' }}" class="visually-hidden">Search the site</label>
        <input
            id="{{ $searchInputId ?? 'hero-search-input' }}"
            type="search"
            name="q"
            placeholder="Search services, tools, news..."
            value="{{ request('q') }}"
        >
        <button type="submit">Search</button>
    </form>
</div>
