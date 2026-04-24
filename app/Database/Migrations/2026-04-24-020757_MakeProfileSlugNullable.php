<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeProfileSlugNullable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('profiles', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('profiles', [
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
        ]);
    }
}
