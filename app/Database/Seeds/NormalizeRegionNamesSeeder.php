<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class NormalizeRegionNamesSeeder extends Seeder
{
    public function run()
    {
        $model = new ProfileModel();
        $client = \Config\Services::curlrequest();
        
        // Fetch all regions from API
        $desaRes = $client->get('http://apps.sinjaikab.go.id/api/pegawai/get_wilayah?tipe=Desa');
        $desaData = json_decode($desaRes->getBody(), true);
        
        $lurahRes = $client->get('http://apps.sinjaikab.go.id/api/pegawai/get_wilayah?tipe=Kelurahan');
        $lurahData = json_decode($lurahRes->getBody(), true);
        
        $apiNames = [];
        foreach (array_merge($desaData, $lurahData) as $item) {
            $name = $item['desa_nama'] ?? $item['kelurahan_nama'];
            if ($name) {
                $normalized = str_replace(' ', '', strtolower($name));
                $apiNames[$normalized] = $name;
            }
        }
        
        $profiles = $model->whereIn('type', ['lurah', 'kepala-desa'])->findAll();
        foreach ($profiles as $p) {
            $dbName = $p['institution'];
            $normalizedDb = str_replace(' ', '', strtolower($dbName));
            
            if (isset($apiNames[$normalizedDb]) && $apiNames[$normalizedDb] !== $dbName) {
                echo "Normalizing ID {$p['id']}: '{$dbName}' -> '{$apiNames[$normalizedDb]}'\n";
                $model->update($p['id'], ['institution' => $apiNames[$normalizedDb]]);
            }
        }
    }
}
