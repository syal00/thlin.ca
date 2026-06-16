@php
    $navParents = \App\Models\Page::whereIn('slug', [
        'home',
        'products-services',
        'products',
        'partners',
        'about',
        'careers',
        'board',
        'portfolio',
        'contact',
        'news',
    ])->published()->get()->keyBy('slug');

    $navChildren = \App\Models\Page::custom()
        ->published()
        ->where('show_in_navigation', true)
        ->whereNotNull('parent_id')
        ->orderBy('sort_order')
        ->orderBy('title')
        ->get()
        ->groupBy('parent_id');
@endphp

<header class="site-header {{ request()->routeIs('home') ? 'is-home-header' : 'is-inner-header' }}">
    <div class="nav-wrapper">
        <div class="container">
            <div class="header-inner">
                <a href="{{ route('home') }}" class="site-logo" aria-label="THLIN home">
                    <span class="logo-icon">THL</span>
                    <span class="logo-text">THLIN</span>
                </a>

                <button
                    type="button"
                    class="nav-toggle"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-controls="main-nav"
                >
                    <span class="visually-hidden">Open menu</span>
                    <span class="nav-toggle-icon" aria-hidden="true"></span>
                </button>

                <nav class="site-nav" id="main-nav" data-main-nav aria-label="Main navigation">
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>

                        <li>
                            <a href="#">Products &amp; Services</a>
                            <ul>
                                @include('partials.nav-section-links', [
                                    'section' => 'products',
                                    'items' => config('thlin.navigation.products.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['products-services', 'products', 'portfolio']])
                            </ul>
                        </li>

                        <li>
                            <a href="#">Partners</a>
                            <ul>
                                @include('partials.nav-section-links', [
                                    'section' => 'partners',
                                    'items' => config('thlin.navigation.partners.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['partners']])
                            </ul>
                        </li>

                        <li>
                            <a href="#">About</a>
                            <ul>
                                @include('partials.nav-section-links', [
                                    'section' => 'about',
                                    'items' => config('thlin.navigation.about.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['about', 'careers', 'news', 'board']])
                            </ul>
                        </li>

                        <li><a href="{{ route('contact') }}">Contact</a></li>

                        @php
                            $resourcePages = \App\Models\Page::navigationItems()->get();
                        @endphp

                        @if ($resourcePages->count())
                            <li>
                                <a href="#">Resources</a>
                                <ul>
                                    @foreach ($resourcePages as $resourcePage)
                                        <li>
                                            <a href="{{ $resourcePage->full_url }}">
                                                {{ $resourcePage->menu_label }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>

                <a href="{{ route('contact') }}" class="nav-cta">Contact Us</a>
            </div>
        </div>
    </div>

    @hasSection('hero')
        @yield('hero')
    @endif
</header>
