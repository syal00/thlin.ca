<div class="page-header">
    <div class="container">
        <h1>{{ $page->title }}</h1>
        @if ($page->excerpt)
            <p class="page-lead">{{ $page->excerpt }}</p>
        @endif
    </div>
</div>
