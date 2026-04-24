<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class FixEmptySlugsSeeder extends Seeder
{
    public function run()
    {
        helper('url');
        $model = new ProfileModel();
        $profiles = $model->where('slug', '')->orWhere('slug IS NULL', null)->findAll();
        
        foreach ($profiles as $p) {
            $base = $p['name'] ?: $p['position'] ?: 'profile';
            if ($base === '-') $base = $p['position'] ?: 'profile';
            
            $slug = url_title($base, '-', true) ?: 'profile-' . $p['id'];
            
            // Ensure uniqueness
            $count = 1;
            $finalSlug = $slug;
            while ($model->where('slug', $finalSlug)->where('id !=', $p['id'])->countAllResults() > 0) {
                $finalSlug = $slug . '-' . $count++;
            }
            
            $model->update($p['id'], ['slug' => $finalSlug]);
        }
    }
}
