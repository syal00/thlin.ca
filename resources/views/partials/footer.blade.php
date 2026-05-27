<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <strong>{{ $thlin['name'] }}</strong>
                <p style="margin: 0.5rem 0 0; opacity: 0.9;">{{ $thlin['tagline'] }}</p>
            </div>
            <ul class="footer-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('pages.show', ['section' => 'about', 'page' => 'us']) }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ $thlin['social']['thehealthline'] }}" target="_blank" rel="noopener">thehealthline.ca</a></li>
                <li><a href="{{ $thlin['social']['youtube'] }}" target="_blank" rel="noopener">YouTube</a></li>
                <li><a href="{{ $thlin['social']['twitter'] }}" target="_blank" rel="noopener">Twitter</a></li>
            </ul>
        </div>
        <p class="copyright">&copy; {{ date('Y') }} {{ $thlin['name'] }}</p>
    </div>
</footer>
