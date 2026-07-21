<footer class="site-footer t-footer">
    <div class="t-container t-footer-row">
        <a href="{{ route('home') }}" class="t-footer-logo">
            <span class="t-footer-logo-mark">THL</span>
            <span class="t-footer-logo-text">Information Network</span>
        </a>

        <nav class="t-footer-nav" aria-label="Footer">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('pages.show', ['section' => 'products', 'page' => 'healthline']) }}">thehealthline.ca</a>
            @if (app()->environment('local'))
                <a href="{{ route('admin.login') }}">CMS Login</a>
            @endif
        </nav>

        <div class="t-footer-social">
            <a href="{{ config('thlin.social.youtube') }}" target="_blank" rel="noopener" aria-label="YouTube">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.5v-7l6.3 3.5-6.3 3.5Z"/></svg>
            </a>
            <a href="{{ config('thlin.social.twitter') }}" target="_blank" rel="noopener" aria-label="Twitter">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.46 6c-.77.35-1.6.58-2.46.69a4.3 4.3 0 0 0 1.88-2.37 8.6 8.6 0 0 1-2.72 1.04 4.28 4.28 0 0 0-7.29 3.9A12.14 12.14 0 0 1 3.15 4.9a4.28 4.28 0 0 0 1.32 5.71c-.7-.02-1.36-.22-1.94-.53v.05a4.28 4.28 0 0 0 3.43 4.2c-.62.17-1.28.2-1.94.07a4.29 4.29 0 0 0 4 2.98A8.6 8.6 0 0 1 2 18.57a12.1 12.1 0 0 0 6.56 1.92c7.88 0 12.19-6.53 12.19-12.19 0-.19 0-.37-.01-.56A8.7 8.7 0 0 0 22.46 6Z"/></svg>
            </a>
        </div>
    </div>

    <div class="t-footer-bottom">
        <div class="t-container t-footer-bottom-inner">
            <p>&copy; {{ date('Y') }} @include('partials.site-setting', ['key' => 'footer_copyright', 'default' => 'thehealthline.ca Information Network'])</p>
        </div>
    </div>
</footer>
