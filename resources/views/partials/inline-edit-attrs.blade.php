@php
    $inlineModel = $model ?? 'page';
    $inlineType = $type ?? 'text';
@endphp
data-editable="true" data-inline-edit="true" data-model="{{ $inlineModel }}" @isset($key) data-key="{{ $key }}" @else data-id="{{ $id }}" @endisset data-field="{{ $field }}" data-type="{{ $inlineType }}"
