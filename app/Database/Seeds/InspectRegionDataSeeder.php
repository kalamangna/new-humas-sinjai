<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class InspectRegionDataSeeder extends Seeder
{
    public function run()
    {
        $model = new ProfileModel();
        $profiles = $model->whereIn('type', ['lurah', 'kepala-desa'])->findAll();
        
        echo "ID | Type | Name | Position | Institution | Kecamatan\n";
        echo "---|------|------|----------|-------------|----------\n";
        foreach ($profiles as $p) {
            echo "{$p['id']} | {$p['type']} | {$p['name']} | {$p['position']} | {$p['institution']} | {$p['kecamatan']}\n";
        }
    }
}
