@php
    $parent = null;

    foreach ($parentSlugs as $slug) {
        if ($navParents->has($slug)) {
            $parent = $navParents->get($slug);
            break;
        }
    }

    $children = $parent ? ($navChildren->get($parent->id) ?? collect()) : collect();
@endphp

@if ($children->count())
    <li class="nav-cms-divider" aria-hidden="true"><span>CMS Pages</span></li>
    @foreach ($children as $childPage)
        <li>
            <a href="{{ $childPage->full_url }}">
                {{ $childPage->menu_label }}
            </a>
        </li>
    @endforeach
@endif
