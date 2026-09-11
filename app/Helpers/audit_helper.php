<?php

use App\Services\AuditLogService;

if (!function_exists('audit_log')) {
    /**
     * Helper cepat untuk mencatat aktivitas ke audit log
     *
     * @param string      $module      Nama modul (auth, posts, categories, tags, profiles, carousel, users, settings)
     * @param string      $action      Jenis aksi (login, logout, login_failed, create, update, delete)
     * @param string      $description Keterangan aktivitas singkat to-the-point
     * @param int|null    $userId      ID user (opsional, default dari session)
     * @param string|null $userName    Nama user (opsional, default dari session)
     * @param string|null $userRole    Peran user (opsional, default dari session)
     * @return bool
     */
    function audit_log(
        string $module,
        string $action,
        string $description,
        ?int $userId = null,
        ?string $userName = null,
        ?string $userRole = null
    ): bool {
        static $service = null;
        if ($service === null) {
            $service = new AuditLogService();
        }

        return $service->record($module, $action, $description, $userId, $userName, $userRole);
    }
}
