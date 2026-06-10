<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <i class="bi bi-heart-pulse"></i>
            <h1 class="sitename">{{ $thlin['name'] }}</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('home') }}" @if (request()->routeIs('home')) class="active" @endif>Home</a></li>
                @foreach ($navigation as $groupKey => $group)
                    <li class="dropdown">
                        <a href="#"><span>{{ $group['label'] }}</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            @foreach ($group['items'] as $slug => $label)
                                <li>
                                    <a href="{{ route('pages.show', ['section' => $groupKey, 'page' => $slug]) }}">{{ $label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                <li><a href="{{ route('contact') }}" @if (request()->routeIs('contact')) class="active" @endif>Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted" href="{{ route('search') }}">Search</a>

    </div>
</header>
