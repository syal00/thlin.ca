@extends('layouts.app')

@section('title', $page->title.' - '.$thlin['name'])
@section('meta_description', $page->excerpt)

@section('hero')
    <div class="hero">
        <div class="container">
            <h1>{{ $thlin['tagline'] }}</h1>
            <p>Founded in 2001, we're an award-winning digital health non-profit committed to connecting patients and caregivers to services, health and social services providers to other providers and health system planners to information. We're driven by an unrelenting commitment to simplifying system navigation by building useful and usable online tools. Becoming healthier is challenging; finding care shouldn't be.</p>
            <form class="search-bar" action="{{ route('search') }}" method="get" role="search">
                <label for="search-query" class="visually-hidden">Search site</label>
                <input type="search" id="search-query" name="q" placeholder="Search the site" value="{{ request('q') }}">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <section class="section">
        <div class="container">
            <h2>System Navigation Made Easy</h2>
            <p>{{ $page->excerpt }}</p>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container card-grid">
            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}" class="card">
                <h3>Products &amp; Services</h3>
                <p>We build websites, collaboration tools and information portals that meet our clients' needs.</p>
            </a>
            <a href="{{ route('pages.show', ['section' => 'partners', 'page' => 'health-care']) }}" class="card">
                <h3>Tools</h3>
                <p>We can work with health care professionals, social service providers, municipalities and OHTs.</p>
            </a>
            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'portfolio']) }}" class="card">
                <h3>Portfolio</h3>
                <p>Check out some examples of our latest projects!</p>
            </a>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2>thehealthline.ca</h2>
            <p>An authoritative health service directory that makes navigating the health care system easier. With 47,000 detailed records for home, community, primary, acute and long-term care services, Our online service directory is the most widely used, online system navigation tool in Ontario.</p>
            <p><a class="btn btn-primary" href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">Learn More</a></p>
        </div>
    </section>

    <section class="section section-alt">
        <div class="container">
            <div class="stat-grid">
                @foreach ($thlin['stats'] as $stat)
                    <div class="stat">
                        <strong>{{ $stat['value'] }}</strong>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-blue">
        <div class="container">
            <h2>Building Tools to Support our Communities</h2>
            <p>Mapping the mosaic of services available within your community and presenting the information effectively, takes careful work. We can help. Whether you're enhancing an existing community information tool or building patient-centred websites, featuring tools to help find condition-specific information, our tailored sites are built to meet user needs. Simple, easy to use and information-rich.</p>
            <p><a class="btn btn-outline" href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Learn More</a></p>
        </div>
    </section>

    @if ($featuredPortfolio->isNotEmpty())
        <section class="section section-alt">
            <div class="container card-grid">
                @foreach ($featuredPortfolio as $item)
                    <a href="{{ $item->url }}" class="card" target="_blank" rel="noopener">
                        <h3>{{ $item->title }}</h3>
                        <p>{{ $item->excerpt }}</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <h2>Interested in Collaborating?</h2>
            <p>We work with partners to improve information systems and connect the people of Ontario to relevant health and community services. Let's talk about how we can collaborate to solve your clients' needs and your data management needs.</p>
            <p><a href="mailto:{{ $thlin['contact_email'] }}">{{ $thlin['contact_email'] }}</a></p>
        </div>
    </section>
@endsection
