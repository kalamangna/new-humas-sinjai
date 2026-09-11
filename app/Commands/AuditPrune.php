<?php

namespace App\Commands;

use App\Services\AuditLogService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AuditPrune extends BaseCommand
{
    protected $group       = 'Audit';
    protected $name        = 'audit:prune';
    protected $description = 'Bersihkan rekaman audit log yang lebih lama dari retensi hari yang ditentukan (default: 90 hari).';
    protected $usage       = 'audit:prune [--days <days>]';
    protected $arguments   = [];
    protected $options     = [
        '--days' => 'Jumlah hari retensi data log (default: 90).',
    ];

    public function run(array $params)
    {
        $days = (int) ($params['days'] ?? CLI::getOption('days') ?? 90);
        if ($days < 1) {
            CLI::error('Jumlah hari retensi minimal 1 hari.');
            return;
        }

        CLI::write("Membersihkan audit log yang berusia lebih dari {$days} hari...", 'yellow');

        $service = new AuditLogService();
        $deleted = $service->pruneOlderThan($days);

        CLI::write("Selesai. Sebanyak {$deleted} baris log berhasil dibersihkan.", 'green');
    }
}
