<?php

use App\Models\SiteSetting;

if (! function_exists('site_setting')) {
    function site_setting(string $key, ?string $default = null): string
    {
        return SiteSetting::getValue($key, $default);
    }
}
