<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeEselonIVToLurah extends Migration
{
    public function up()
    {
        // 1. Expand the ENUM to allow 'lurah' alongside 'eselon-iv'
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'lurah', 'kepala-desa') NOT NULL");

        // 2. Update existing 'eselon-iv' records to 'lurah'
        $this->db->table('profiles')->where('type', 'eselon-iv')->update(['type' => 'lurah']);

        // 3. Shrink the ENUM to remove 'eselon-iv'
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'lurah', 'kepala-desa') NOT NULL");
    }

    public function down()
    {
        // 1. Expand the ENUM to allow 'eselon-iv' alongside 'lurah'
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'lurah', 'kepala-desa') NOT NULL");

        // 2. Revert existing 'lurah' records to 'eselon-iv'
        $this->db->table('profiles')->where('type', 'lurah')->update(['type' => 'eselon-iv']);

        // 3. Shrink the ENUM to remove 'lurah'
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa') NOT NULL");
    }
}
