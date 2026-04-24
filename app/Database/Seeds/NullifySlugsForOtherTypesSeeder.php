<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NullifySlugsForOtherTypesSeeder extends Seeder
{
    public function run()
    {
        $this->db->query("UPDATE profiles SET slug = NULL WHERE type NOT IN ('bupati', 'wakil-bupati', 'sekda')");
    }
}
