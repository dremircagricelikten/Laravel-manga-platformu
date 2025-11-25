<?php

if (!function_exists('setting')) {
    /**
     * Get / set the specified setting value.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        if (is_null($key)) {
            return $default;
        }

        // Try to get setting from database
        try {
            $setting = \Illuminate\Support\Facades\Cache::remember("setting.{$key}", 3600, function () use ($key) {
                return \App\Models\SiteSetting::where('key', $key)->value('value');
            });

            return $setting ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
