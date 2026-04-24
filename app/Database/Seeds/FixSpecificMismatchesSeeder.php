<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class FixSpecificMismatchesSeeder extends Seeder
{
    public function run()
    {
        $model = new ProfileModel();
        
        $mismatches = [
            'Sangiaseri' => 'Sangiasseri',
            'Bulukamase' => 'Bulu Kamase',
            'Samaturu'   => 'Samaturue',
        ];
        
        foreach ($mismatches as $old => $new) {
            $profiles = $model->where('institution', $old)->findAll();
            foreach ($profiles as $p) {
                echo "Fixing ID {$p['id']}: '{$old}' -> '{$new}'\n";
                $model->update($p['id'], ['institution' => $new]);
            }
        }
    }
}
