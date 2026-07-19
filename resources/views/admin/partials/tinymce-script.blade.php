@php
    $useTinyCloud = ! config('services.tinymce.self_hosted', true) && filled(config('services.tinymce.key'));
@endphp

@if ($useTinyCloud)
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
@else
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}?v={{ @filemtime(public_path('vendor/tinymce/tinymce.min.js')) ?: '1' }}"></script>
@endif
