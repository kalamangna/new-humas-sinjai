<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveAktifEntries extends Migration
{
    public function up()
    {
        // Remove from tags
        $this->db->table('tags')->where('name', 'Aktif')->delete();
        
        // Remove from categories
        $this->db->table('categories')->where('name', 'Aktif')->delete();
    }

    public function down()
    {
        // No easy way to restore deleted records without original data
    }
}