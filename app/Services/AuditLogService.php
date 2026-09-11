<?php

namespace App\Services;

use App\Models\AuditLogModel;

class AuditLogService
{
    protected AuditLogModel $logModel;

    public function __construct()
    {
        $this->logModel = new AuditLogModel();
    }

    /**
     * Catat log aktivitas sistem
     */
    public function record(
        string $module,
        string $action,
        string $description,
        ?int $userId = null,
        ?string $userName = null,
        ?string $userRole = null
    ): bool {
        $request = service('request');

        $sessionUserId   = $userId ?? session('user_id');
        $sessionUserName = $userName ?? session('name');
        $sessionUserRole = $userRole ?? session('role');

        $ipAddress = $request ? $request->getIPAddress() : null;
        $userAgent = $request && $request->getUserAgent() ? substr($request->getUserAgent()->getAgentString(), 0, 255) : null;

        $data = [
            'user_id'     => $sessionUserId ? (int) $sessionUserId : null,
            'user_name'   => $sessionUserName,
            'user_role'   => $sessionUserRole,
            'module'      => strtolower($module),
            'action'      => strtolower($action),
            'description' => substr($description, 0, 255),
            'ip_address'  => $ipAddress,
            'user_agent'  => $userAgent,
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        try {
            return (bool) $this->logModel->insert($data);
        } catch (\Throwable $e) {
            // Jika tabel audit_logs belum ada di production (tanpa akses spark migrate), buat otomatis
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), "audit_logs") !== false) {
                $this->createTableIfNotExists();
                try {
                    return (bool) $this->logModel->insert($data);
                } catch (\Throwable $ex) {
                    log_message('error', '[AuditLogService] Retry insert failed: ' . $ex->getMessage());
                }
            } else {
                log_message('error', '[AuditLogService] Failed to record audit log: ' . $e->getMessage());
            }
            return false;
        }
    }

    /**
     * Ambil data log dengan filter dan pagination
     */
    public function getLogs(array $filters = [], int $perPage = 20): array
    {
        $this->createTableIfNotExists();
        $builder = $this->logModel->orderBy('id', 'DESC');

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $builder->groupStart()
                ->like('description', $search)
                ->orLike('user_name', $search)
                ->orLike('ip_address', $search)
                ->groupEnd();
        }

        if (!empty($filters['module'])) {
            $builder->where('module', strtolower($filters['module']));
        }

        if (!empty($filters['action'])) {
            $builder->where('action', strtolower($filters['action']));
        }

        if (!empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }

        if (!empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        return [
            'logs'  => $builder->paginate($perPage),
            'pager' => $this->logModel->pager,
        ];
    }

    /**
     * Hitung ringkasan statistik log
     */
    public function getStats(): array
    {
        $this->createTableIfNotExists();
        $today = date('Y-m-d 00:00:00');

        return [
            'total'      => $this->logModel->countAllResults(false),
            'today'      => $this->logModel->where('created_at >=', $today)->countAllResults(false),
            'auth'       => $this->logModel->where('module', 'auth')->countAllResults(false),
            'activities' => $this->logModel->where('module !=', 'auth')->countAllResults(false),
        ];
    }

    /**
     * Bersihkan log yang berusia lebih dari N hari
     */
    public function pruneOlderThan(int $days = 90): int
    {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $this->logModel->where('created_at <', $cutoff)->delete();

        return $this->logModel->db->affectedRows();
    }

    /**
     * Buat tabel audit_logs otomatis jika belum ada (self-healing untuk server tanpa akses terminal)
     */
    public function createTableIfNotExists(): void
    {
        try {
            $db = \Config\Database::connect();
            if (!$db->tableExists('audit_logs')) {
                $sql = "CREATE TABLE IF NOT EXISTS `audit_logs` (
                    `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `user_id` INT(5) UNSIGNED DEFAULT NULL,
                    `user_name` VARCHAR(100) DEFAULT NULL,
                    `user_role` VARCHAR(50) DEFAULT NULL,
                    `module` VARCHAR(50) NOT NULL,
                    `action` VARCHAR(50) NOT NULL,
                    `description` VARCHAR(255) NOT NULL,
                    `ip_address` VARCHAR(45) DEFAULT NULL,
                    `user_agent` VARCHAR(255) DEFAULT NULL,
                    `created_at` DATETIME DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `created_at` (`created_at`),
                    KEY `module` (`module`),
                    KEY `action` (`action`),
                    KEY `user_id` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                $db->query($sql);
            }
        } catch (\Throwable $e) {
            log_message('error', '[AuditLogService] Failed to auto-create audit_logs table: ' . $e->getMessage());
        }
    }
}
