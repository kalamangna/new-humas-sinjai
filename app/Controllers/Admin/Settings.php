<?php

namespace App\Controllers\Admin;

use App\Services\SettingService;

class Settings extends BaseController
{
    protected $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function index()
    {
        $data = [
            'grouped_settings' => $this->settingService->getForAdmin(),
            'title' => 'Pengaturan Situs'
        ];

        return $this->render('admin/settings/index', $data);
    }

    public function update()
    {
        $postData = $this->request->getPost('settings');
        
        if ($this->settingService->updateBatch($postData)) {
            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
        }

        return redirect()->back()->with('error', $this->settingService->getError() ?: 'Terjadi kesalahan saat memperbarui pengaturan');
    }
}
