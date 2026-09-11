<?php

namespace App\Controllers\Admin;

use App\Services\AuditLogService;

class AuditLogs extends BaseController
{
    protected AuditLogService $auditService;

    public function __construct()
    {
        $this->auditService = new AuditLogService();
    }

    public function index()
    {
        // Hanya role admin yang diizinkan mengakses
        if (session()->get('role') !== 'admin') {
            return redirect()->to(base_url('admin'))->with('error', 'Akses ditolak. Halaman khusus Administrator.');
        }

        $filters = [
            'search'     => $this->request->getGet('search'),
            'module'     => $this->request->getGet('module'),
            'action'     => $this->request->getGet('action'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        $logsData = $this->auditService->getLogs($filters, 20);
        $stats = $this->auditService->getStats();

        $data = array_merge($logsData, [
            'filters' => $filters,
            'stats'   => $stats,
            'modules' => [
                'auth'       => 'Autentikasi',
                'posts'      => 'Berita',
                'categories' => 'Kategori',
                'tags'       => 'Tag',
                'profiles'   => 'Profil',
                'carousel'   => 'Slide',
                'users'      => 'User',
                'settings'   => 'Pengaturan',
            ],
            'actions' => [
                'login'        => 'Login',
                'logout'       => 'Logout',
                'login_failed' => 'Login Gagal',
                'create'       => 'Tambah',
                'update'       => 'Ubah',
                'delete'       => 'Hapus',
            ],
        ]);

        return $this->render('admin/audit_logs/index', $data);
    }
}
