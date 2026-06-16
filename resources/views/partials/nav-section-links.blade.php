@foreach ($items as $slug => $label)
    <li>
        <a href="{{ route('pages.show', ['section' => $section, 'page' => $slug]) }}">{{ $label }}</a>
    </li>
@endforeach
