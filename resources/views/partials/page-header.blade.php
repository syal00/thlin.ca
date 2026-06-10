<div class="page-title light-background">
    <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a></li>
                @if (! empty($breadcrumbs))
                    @foreach ($breadcrumbs as $crumb)
                        @if (! empty($crumb['url']))
                            <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                        @else
                            <li class="breadcrumb-item active current">{{ $crumb['label'] }}</li>
                        @endif
                    @endforeach
                @endif
                <li class="breadcrumb-item active current">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>

    <div class="title-wrapper">
        <h1
            data-editable="true"
            data-model="page"
            data-id="{{ $page->id }}"
            data-field="title"
        >{{ $page->title }}</h1>
        @if ($page->excerpt)
            <p
                data-editable="true"
                data-model="page"
                data-id="{{ $page->id }}"
                data-field="excerpt"
            >{{ $page->excerpt }}</p>
        @endif
    </div>
</div>
