{{-- Reusable breadcrumb. Pass $breadcrumbs as an array of
     ['label' => ..., 'url' => ...] with the current page as the last
     item (either omit 'url' or set 'current' => true). --}}
@if (! empty($breadcrumbs))
    <nav class="t-breadcrumb" aria-label="Breadcrumb">
        @foreach ($breadcrumbs as $crumb)
            @if (! empty($crumb['url']) && empty($crumb['current']))
                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
            @else
                <span aria-current="page">{{ $crumb['label'] }}</span>
            @endif

            @if (! $loop->last)
                <span class="t-breadcrumb-sep" aria-hidden="true">/</span>
            @endif
        @endforeach
    </nav>
@endif
