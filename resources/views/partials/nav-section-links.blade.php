@foreach ($items as $slug => $label)
    @php
        $page = $navParents->get($slug);
    @endphp
    <li>
        <a href="{{ route('pages.show', ['section' => $section, 'page' => $slug]) }}">
            @if ($page)
                <span @include('partials.inline-edit-attrs', ['model' => 'page', 'id' => $page->id, 'field' => 'navigation_label', 'type' => 'text'])>{{ $page->menu_label }}</span>
            @else
                {{ $label }}
            @endif
        </a>
    </li>
@endforeach
