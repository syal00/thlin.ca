@extends('admin.layout')

@section('title', 'Site settings')
@section('page_title', 'Site settings')
@section('page_subtitle', 'Edit homepage copy, navigation labels, footer text, and other global content.')

@section('content')
    <div class="admin-page-actions">
        <a href="{{ url('/?edit=1') }}" target="_blank" rel="noopener" class="btn btn-light">Open live editor</a>
    </div>

    <form method="post" action="{{ route('admin.settings.update') }}" class="admin-form">
        @csrf
        @method('PATCH')

        @foreach ($groups as $group => $settings)
            <div class="admin-card cms-step-card" style="margin-bottom: 1.5rem;">
                <div class="cms-step-header">
                    <div>
                        <h2>{{ $groupLabels[$group] ?? ucfirst($group) }}</h2>
                        <p>{{ $settings->count() }} editable {{ Str::plural('field', $settings->count()) }}</p>
                    </div>
                </div>

                @foreach ($settings as $setting)
                    <div class="form-group">
                        <label class="form-label" for="setting-{{ $setting->key }}">
                            {{ Str::headline(str_replace('_', ' ', preg_replace('/^(nav_|home_|footer_|cta_|contact_|portfolio_)/', '', $setting->key))) }}
                            <span class="form-hint">({{ $setting->key }})</span>
                        </label>

                        @if ($setting->type === 'textarea' || $setting->type === 'richtext')
                            <textarea
                                class="form-control"
                                id="setting-{{ $setting->key }}"
                                name="settings[{{ $setting->key }}]"
                                rows="{{ $setting->type === 'richtext' ? 6 : 3 }}"
                            >{{ old('settings.'.$setting->key, $setting->value) }}</textarea>
                        @else
                            <input
                                class="form-control"
                                type="text"
                                id="setting-{{ $setting->key }}"
                                name="settings[{{ $setting->key }}]"
                                value="{{ old('settings.'.$setting->key, $setting->value) }}"
                            >
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save all settings</button>
        </div>
    </form>
@endsection
