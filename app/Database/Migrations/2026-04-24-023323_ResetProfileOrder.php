<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ResetProfileOrder extends Migration
{
    public function up()
    {
        // Reset 'order' column to 0 for specific types as they now use regional/type-based sorting
        $this->db->query("UPDATE profiles SET `order` = 0 WHERE type IN ('bupati', 'wakil-bupati', 'sekda', 'lurah', 'kepala-desa')");
    }

    public function down()
    {
        // No easy way to revert specific order values
    }
}
