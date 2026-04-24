<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateLurahDataFormat extends Migration
{
    public function up()
    {
        // 1. Move the specific Kelurahan name from position to institution (which we now use for the Kelurahan dropdown)
        // Only applies if the position still has the 'Lurah ' prefix.
        // 2. Set the position itself strictly to 'Lurah'.
        $this->db->query("UPDATE profiles SET institution = TRIM(SUBSTRING(position, 7)), position = 'Lurah' WHERE type = 'lurah' AND position LIKE 'Lurah %'");
    }

    public function down()
    {
        // To revert, we prepend 'Lurah ' back to the institution to rebuild the position, and can't fully revert institution to whatever it was before perfectly.
        $this->db->query("UPDATE profiles SET position = CONCAT('Lurah ', institution) WHERE type = 'lurah'");
    }
}
