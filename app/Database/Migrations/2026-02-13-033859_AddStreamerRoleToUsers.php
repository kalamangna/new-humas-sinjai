<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStreamerRoleToUsers extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'author', 'streamer'],
                'default'    => 'author',
            ],
        ]);
    }

    public function down()
    {
        // Revert to original roles. Note: Any 'streamer' will be problematic if we revert.
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'author'],
                'default'    => 'author',
            ],
        ]);
    }
}