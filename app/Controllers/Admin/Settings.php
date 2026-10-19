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
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
        }

        $data = [
            'grouped_settings' => $this->settingService->getForAdmin(),
            'title' => 'Pengaturan Situs'
        ];

        return $this->render('admin/settings/index', $data);
    }

    public function update()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses untuk melakukan tindakan ini.');
        }

        $postData = $this->request->getPost('settings');
        
        if ($this->settingService->updateBatch($postData)) {
            audit_log('settings', 'update', 'Memperbarui konfigurasi situs');
            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
        }

        return redirect()->back()->with('error', $this->settingService->getError() ?: 'Terjadi kesalahan saat memperbarui pengaturan');
    }
}
