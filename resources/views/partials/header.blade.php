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

    $homePage = $navParents->get('home');
    $contactPage = $navParents->get('contact');

    $isHomeActive = request()->routeIs('home');
    $isProductsActive = request()->is('products*') || (request()->routeIs('pages.show') && request()->route('section') === 'products');
    $isPartnersActive = request()->is('partners*') || (request()->routeIs('pages.show') && request()->route('section') === 'partners');
    $isAboutActive = request()->is('about*') || (request()->routeIs('pages.show') && request()->route('section') === 'about');
    $isContactActive = request()->routeIs('contact') || request()->is('contact');
@endphp

<header class="site-header {{ request()->routeIs('home') ? 'is-home-header' : 'is-inner-header' }}">
    <div class="nav-wrapper">
        <div class="container">
            <div class="header-inner nav-shell">
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

                <nav class="site-nav site-nav-wrapper main-nav" id="main-nav" data-main-nav aria-label="Main navigation">
                    <ul class="nav-menu">
                        <li>
                            <a href="{{ route('home') }}" class="nav-link{{ $isHomeActive ? ' is-active' : '' }}">
                                @if ($homePage)
                                    <span @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $homePage->id, 'field' => 'navigation_label', 'type' => 'text'])>Home</span>
                                @else
                                    Home
                                @endif
                            </a>
                        </li>

                        <li class="nav-dropdown">
                            <a
                                href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}"
                                class="nav-link{{ $isProductsActive ? ' is-active' : '' }}"
                                aria-haspopup="true"
                            >@include('partials.site-setting', ['key' => 'nav_products_label', 'default' => 'Products & Services'])</a>
                            <ul class="nav-dropdown-menu">
                                <li>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a>
                                </li>
                                <li>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'patient-portals']) }}">Patient Portals</a>
                                </li>
                                <li>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'provider-portals']) }}">Provider Portals</a>
                                </li>
                                <li>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'support-training']) }}">Support &amp; Training</a>
                                </li>
                                <li>
                                    <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'information-management']) }}">Information Management</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="nav-link{{ $isPartnersActive ? ' is-active' : '' }}">@include('partials.site-setting', ['key' => 'nav_partners_label', 'default' => 'Partners'])</a>
                            <ul>
                                @include('partials.nav-section-links', [
                                    'section' => 'partners',
                                    'items' => config('thlin.navigation.partners.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['partners']])
                            </ul>
                        </li>

                        <li>
                            <a href="#" class="nav-link{{ $isAboutActive ? ' is-active' : '' }}">@include('partials.site-setting', ['key' => 'nav_about_label', 'default' => 'About'])</a>
                            <ul>
                                @include('partials.nav-section-links', [
                                    'section' => 'about',
                                    'items' => config('thlin.navigation.about.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['about', 'careers', 'news', 'board']])
                            </ul>
                        </li>

                        <li>
                            <a href="{{ route('contact') }}" class="nav-link{{ $isContactActive ? ' is-active' : '' }}">
                                @if ($contactPage)
                                    <span @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $contactPage->id, 'field' => 'navigation_label', 'type' => 'text'])>{{ $contactPage->menu_label }}</span>
                                @else
                                    Contact
                                @endif
                            </a>
                        </li>

                        @php
                            $resourcePages = \App\Models\Page::navigationItems()->get();
                        @endphp

                        @if ($resourcePages->count())
                            <li>
                                <a href="#">@include('partials.site-setting', ['key' => 'nav_resources_label', 'default' => 'Resources'])</a>
                                <ul>
                                    @foreach ($resourcePages as $resourcePage)
                                        <li>
                                            <a href="{{ $resourcePage->full_url }}">
                                                <span @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $resourcePage->id, 'field' => 'navigation_label', 'type' => 'text'])>{{ $resourcePage->menu_label }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </nav>

                <a href="{{ route('contact') }}" class="nav-cta">@include('partials.site-setting', ['key' => 'nav_cta_label', 'default' => 'Contact Us'])</a>
            </div>
        </div>
    </div>

    @hasSection('hero')
        @yield('hero')
    @endif
</header>
