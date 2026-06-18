<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function getValue(string $key, ?string $default = null): string
    {
        $settings = static::cached();
        $value = $settings[$key] ?? null;

        return ($value !== null && $value !== '') ? $value : ($default ?? '');
    }

    /** @return array<string, string|null> */
    public static function cached(): array
    {
        return Cache::remember('site_settings', 3600, fn () => static::query()->pluck('value', 'key')->all());
    }

    public static function forgetCache(): void
    {
        Cache::forget('site_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::forgetCache());
        static::deleted(fn () => static::forgetCache());
    }
}
