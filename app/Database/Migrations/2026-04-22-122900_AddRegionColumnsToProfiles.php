<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegionColumnsToProfiles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('profiles', [
            'kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'kelurahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'desa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('profiles', 'kecamatan');
        $this->forge->dropColumn('profiles', 'kelurahan');
        $this->forge->dropColumn('profiles', 'desa');
    }
}
