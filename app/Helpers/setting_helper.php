<?php

if (!function_exists('get_setting')) {
    /**
     * Get site setting by key
     */
    function get_setting(string $key, $default = null)
    {
        $service = new \App\Services\SettingService();
        return $service->get($key, $default);
    }
}
