{{-- $result is a plain array from App\Services\SiteSearch: type/title/excerpt/url --}}
<li class="t-search-result">
    @if (!empty($result['type']))
        <span class="t-badge">{{ $result['type'] }}</span>
    @endif
    <h3>
        <a href="{{ $result['url'] ?? '#' }}">
            {{ $result['title'] ?? 'Untitled' }}
        </a>
    </h3>

    @if (!empty($result['excerpt']))
        <p>{{ $result['excerpt'] }}</p>
    @endif
</li>
