@php
    use App\Models\SiteSetting;

    $tag = $tag ?? 'span';
    $settingKey = $key;
    $settingType = $type ?? 'text';
    $settingValue = SiteSetting::getValue($settingKey, $default ?? '');
    $inlineType = in_array($settingType, ['richtext', 'textarea'], true) ? 'richtext' : $settingType;
    $isRichOutput = $inlineType === 'richtext';
    $settingClass = $class ?? null;
@endphp
<{{ $tag }}@if ($settingClass) class="{{ $settingClass }}"@endif @include('partials.inline-edit-attrs', ['model' => 'sitesetting', 'key' => $settingKey, 'field' => 'value', 'type' => $inlineType])>@if ($isRichOutput){!! $settingValue !!}@else{{ $settingValue }}@endif</{{ $tag }}>
