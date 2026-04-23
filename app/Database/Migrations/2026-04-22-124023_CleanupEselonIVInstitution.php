<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CleanupEselonIVInstitution extends Migration
{
    public function up()
    {
        // Update institution for eselon-iv profiles to remove "Pemerintah " prefix
        $this->db->table('profiles')
            ->where('type', 'eselon-iv')
            ->like('institution', 'Pemerintah Kecamatan ')
            ->set('institution', "REPLACE(institution, 'Pemerintah ', '')", false)
            ->update();
    }

    public function down()
    {
        // Add back "Pemerintah " prefix if rolled back
        $this->db->table('profiles')
            ->where('type', 'eselon-iv')
            ->like('institution', 'Kecamatan ')
            ->notLike('institution', 'Pemerintah Kecamatan ')
            ->set('institution', "CONCAT('Pemerintah ', institution)", false)
            ->update();
    }
}
