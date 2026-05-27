<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark" aria-hidden="true">THL</span>
            <span>{{ $thlin['name'] }}</span>
        </a>

        <button type="button" class="nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="main-navigation">
            Menu
        </button>

        <nav class="main-nav" id="main-navigation" data-main-nav aria-label="Main navigation">
            <ul>
                @foreach ($navigation as $groupKey => $group)
                    <li>
                        <a href="#" aria-haspopup="true">{{ $group['label'] }}</a>
                        <ul class="submenu">
                            @foreach ($group['items'] as $slug => $label)
                                <li>
                                    <a href="{{ route('pages.show', ['section' => $groupKey, 'page' => $slug]) }}">{{ $label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </nav>
    </div>

    @hasSection('hero')
        @yield('hero')
    @endif
</header>
