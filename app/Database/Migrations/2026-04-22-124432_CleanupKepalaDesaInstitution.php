<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupKepalaDesaInstitution extends Migration
{
    public function up()
    {
        // Update institution for kepala-desa profiles to remove "Pemerintah " prefix
        $this->db->table('profiles')
            ->where('type', 'kepala-desa')
            ->like('institution', 'Pemerintah Desa ')
            ->set('institution', "REPLACE(institution, 'Pemerintah ', '')", false)
            ->update();
    }

    public function down()
    {
        // Add back "Pemerintah " prefix if rolled back
        $this->db->table('profiles')
            ->where('type', 'kepala-desa')
            ->notLike('institution', 'Pemerintah Desa ')
            ->set('institution', "CONCAT('Pemerintah ', institution)", false)
            ->update();
    }
}
