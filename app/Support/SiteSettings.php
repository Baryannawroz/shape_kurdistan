<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    /**
     * @return array<string, string|null>
     */
    public static function allKeyed(): array
    {
        return Cache::remember('site_settings_keyed', 60, function (): array {
            return Setting::query()->pluck('value', 'key')->all();
        });
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return self::allKeyed()[$key] ?? $default;
    }

    public static function forgetCache(): void
    {
        Cache::forget('site_settings_keyed');
    }
}
