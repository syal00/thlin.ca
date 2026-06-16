@php
    $inlineModel = $model ?? 'page';
    $inlineType = $type ?? 'text';
@endphp
data-editable="true" data-inline-edit="true" data-model="{{ $inlineModel }}" data-id="{{ $id }}" data-field="{{ $field }}" data-type="{{ $inlineType }}"
