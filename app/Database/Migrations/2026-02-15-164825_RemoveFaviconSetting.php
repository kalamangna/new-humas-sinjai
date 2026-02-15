<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveFaviconSetting extends Migration
{
    public function up()
    {
        $this->db->table('site_settings')->where('key', 'site_favicon')->delete();
    }

    public function down()
    {
        // Optional: Re-add if rolled back
        $data = [
            'key'   => 'site_favicon',
            'value' => 'logo.png',
            'group' => 'general',
            'type'  => 'image',
            'label' => 'Favicon Situs',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('site_settings')->insert($data);
    }
}