<?php

// app/Helpers/settings.php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     */
    function setting(string $key, $default = null)
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings or settings by group
     */
    function settings(?string $group = null): array
    {
        $settings = Cache::rememberForever('site_settings', function () {
            return Setting::all();
        });

        if ($group) {
            return $settings->where('group', $group)->pluck('value', 'key')->toArray();
        }

        return $settings->pluck('value', 'key')->toArray();
    }
}
