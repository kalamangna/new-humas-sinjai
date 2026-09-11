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

        return (bool) $this->logModel->insert([
            'user_id'     => $sessionUserId ? (int) $sessionUserId : null,
            'user_name'   => $sessionUserName,
            'user_role'   => $sessionUserRole,
            'module'      => strtolower($module),
            'action'      => strtolower($action),
            'description' => substr($description, 0, 255),
            'ip_address'  => $ipAddress,
            'user_agent'  => $userAgent,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Ambil data log dengan filter dan pagination
     */
    public function getLogs(array $filters = [], int $perPage = 20): array
    {
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
}
