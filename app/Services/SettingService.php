<?php

namespace App\Services;

use App\Models\SiteSettingModel;

class SettingService extends BaseService
{
    protected $model;
    protected $cacheKey = 'site_settings_all';

    public function __construct()
    {
        $this->model = new SiteSettingModel();
    }

    /**
     * Get all settings as key-value pairs
     */
    public function getAll(): array
    {
        $settings = cache($this->cacheKey);

        if ($settings === null) {
            $raw = $this->model->findAll();
            $settings = [];
            foreach ($raw as $item) {
                $value = $item['value'];
                if ($item['type'] === 'json') {
                    $value = json_decode($value, true);
                }
                $settings[$item['key']] = $value;
            }
            cache()->save($this->cacheKey, $settings, 3600 * 24); // Cache for 24 hours
        }

        return $settings;
    }

    /**
     * Get a specific setting by key
     */
    public function get(string $key, $default = null)
    {
        $all = $this->getAll();
        return $all[$key] ?? $default;
    }

    /**
     * Update settings
     */
    public function updateBatch(array $data): bool
    {
        $this->db = \Config\Database::connect();
        $this->db->transStart();

        foreach ($data as $key => $value) {
            $setting = $this->model->where('key', $key)->first();
            if ($setting) {
                $finalValue = $value;
                if ($setting['type'] === 'json' && !is_string($value)) {
                    $finalValue = json_encode($value);
                }
                $this->model->update($setting['id'], ['value' => $finalValue]);
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->setError('Failed to update settings');
            return false;
        }

        // Clear cache
        cache()->delete($this->cacheKey);
        return true;
    }

    /**
     * Get raw settings with metadata for admin form
     */
    public function getForAdmin(): array
    {
        $raw = $this->model->findAll();
        $grouped = [];
        foreach ($raw as $item) {
            $grouped[$item['group']][] = $item;
        }
        return $grouped;
    }
}
