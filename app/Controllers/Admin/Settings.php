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
            audit_log('settings', 'update', 'Memperbarui konfigurasi situs');
            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui');
        }

        return redirect()->back()->with('error', $this->settingService->getError() ?: 'Terjadi kesalahan saat memperbarui pengaturan');
    }

    public function generateOg()
    {
        helper(['image', 'media']);

        $postModel = new \App\Models\PostModel();
        $posts = $postModel->where('status', 'published')
            ->where('thumbnail IS NOT NULL')
            ->where('thumbnail !=', '')
            ->orderBy('published_at', 'DESC')
            ->findAll();

        $generated = 0;
        $skipped = 0;

        foreach ($posts as $post) {
            $slug = $post['slug'] ?? '';
            if (empty($slug)) continue;

            $targetOg = FCPATH . 'uploads/og/' . $slug . '.jpg';
            if (file_exists($targetOg)) {
                $skipped++;
                continue;
            }

            $sourcePath = resolve_local_media_path($post['thumbnail']);
            if ($sourcePath && generateOgImage($sourcePath, $targetOg)) {
                $generated++;
            }
        }

        audit_log('settings', 'update', "Menjalankan sinkronisasi gambar Open Graph ({$generated} dibuat, {$skipped} dilewati)");
        return redirect()->back()->with('success', "Sinkronisasi selesai! {$generated} gambar OG baru berhasil dibuat ({$skipped} sudah ada).");
    }
}
