@php
    $navParents = \App\Models\Page::whereIn('slug', [
        'home',
        'products-services',
        'products',
        'healthline',
        'healthchat',
        'patient-portals',
        'provider-portals',
        'support-training',
        'information-management',
        'portfolio',
        'resources',
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

    $contactPage = $navParents->get('contact');

    $isProductsActive = request()->is('products*') || (request()->routeIs('pages.show') && request()->route('section') === 'products');
    $isPartnersActive = request()->is('partners*') || (request()->routeIs('pages.show') && request()->route('section') === 'partners');
    $isAboutActive = request()->is('about*') || (request()->routeIs('pages.show') && request()->route('section') === 'about');
    $isContactActive = request()->routeIs('contact') || request()->is('contact');
@endphp

<header class="site-header t-header {{ request()->routeIs('home') ? 'is-home-header' : 'is-inner-header' }}">
    <div class="nav-wrapper">
        <div class="t-container">
            <div class="header-inner nav-shell t-header-inner">
                <a href="{{ route('home') }}" class="site-logo t-logo" aria-label="THLIN home">
                    <span class="logo-icon t-logo-mark">THL</span>
                    <span class="logo-text t-logo-text">THLIN</span>
                    <span class="logo-text-full t-logo-text-full">THL Information Network</span>
                </a>

                <button
                    type="button"
                    class="nav-toggle t-nav-toggle"
                    data-nav-toggle
                    aria-expanded="false"
                    aria-controls="main-nav"
                >MENU</button>

                <nav class="site-nav site-nav-wrapper main-nav t-nav" id="main-nav" data-main-nav aria-label="Main navigation">
                    <ul class="nav-menu t-nav-menu">
                        <li class="nav-dropdown t-nav-dropdown">
                            <a href="{{ url('/products-services') }}" class="nav-link t-nav-link{{ $isProductsActive ? ' is-active' : '' }}">@include('partials.site-setting', ['key' => 'nav_products_label', 'default' => 'Products & Services'])</a>
                            <ul class="nav-dropdown-menu t-nav-dropdown-menu">
                                @include('partials.nav-section-links', [
                                    'section' => 'products',
                                    'items' => config('thlin.navigation.products.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['products-services', 'products', 'portfolio']])
                            </ul>
                        </li>

                        <li class="nav-dropdown t-nav-dropdown">
                            <a href="{{ url('/partners') }}" class="nav-link t-nav-link{{ $isPartnersActive ? ' is-active' : '' }}">@include('partials.site-setting', ['key' => 'nav_partners_label', 'default' => 'Partners'])</a>
                            <ul class="nav-dropdown-menu t-nav-dropdown-menu">
                                @include('partials.nav-section-links', [
                                    'section' => 'partners',
                                    'items' => config('thlin.navigation.partners.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['partners']])
                            </ul>
                        </li>

                        <li class="nav-dropdown t-nav-dropdown">
                            <a href="{{ url('/about') }}" class="nav-link t-nav-link{{ $isAboutActive ? ' is-active' : '' }}">@include('partials.site-setting', ['key' => 'nav_about_label', 'default' => 'About'])</a>
                            <ul class="nav-dropdown-menu t-nav-dropdown-menu">
                                @include('partials.nav-section-links', [
                                    'section' => 'about',
                                    'items' => config('thlin.navigation.about.items'),
                                ])
                                @include('partials.nav-cms-children', ['parentSlugs' => ['about', 'careers', 'news', 'board']])
                            </ul>
                        </li>

                        <li class="t-nav-item-contact">
                            <a href="{{ route('contact') }}" class="nav-link t-nav-link{{ $isContactActive ? ' is-active' : '' }}">
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
                            <li class="nav-dropdown t-nav-dropdown" data-nav-dropdown>
                                <button
                                    type="button"
                                    class="t-nav-link"
                                    aria-expanded="false"
                                    aria-haspopup="true"
                                    aria-controls="nav-dropdown-resources"
                                    data-nav-dropdown-trigger
                                >@include('partials.site-setting', ['key' => 'nav_resources_label', 'default' => 'Resources'])</button>
                                <ul class="nav-dropdown-menu t-nav-dropdown-menu" id="nav-dropdown-resources" data-nav-dropdown-menu>
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

                <div class="t-header-actions">
                    <a href="{{ route('contact') }}" class="nav-cta t-btn t-btn-primary">@include('partials.site-setting', ['key' => 'nav_cta_label', 'default' => 'Contact Us'])</a>
                </div>
            </div>
        </div>
    </div>
</header>
