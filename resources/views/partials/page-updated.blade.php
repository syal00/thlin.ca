@if (! empty($page) && $page->updated_at)
    <p class="content-updated">Last updated: {{ $page->updated_at->format('F j, Y') }}</p>
@endif
