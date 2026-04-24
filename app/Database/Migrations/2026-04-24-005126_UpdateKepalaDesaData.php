<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateKepalaDesaData extends Migration
{
    public function up()
    {
        // 1. Update position to exactly 'Kepala Desa'
        // 2. Remove the 'Desa ' prefix from the institution column
        $this->db->query("UPDATE profiles SET position = 'Kepala Desa', institution = TRIM(LEADING 'Desa ' FROM institution) WHERE type = 'kepala-desa'");
    }

    public function down()
    {
        // To revert, we can append 'Desa ' back to the institution, and the position cannot easily be reverted perfectly.
        $this->db->query("UPDATE profiles SET institution = CONCAT('Desa ', institution) WHERE type = 'kepala-desa'");
    }
}
