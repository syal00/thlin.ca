@php
    use App\Support\CmsBodyFormatter;

    $html = $html ?? '';
@endphp
{!! CmsBodyFormatter::format($html) !!}
