<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class ProfileCsvSeeder extends Seeder
{
    public function run()
    {
        $csvFile = ROOTPATH . 'Daftar Kepala Desa dan Lurah Sinjai V3 (Revisi Nama & Gelar) - Daftar Kepala Desa dan Lurah Sinjai V3 (Revisi Nama & Gelar).csv';
        if (!file_exists($csvFile)) {
            echo "CSV file not found at: {$csvFile}\n";
            return;
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Skip header

        $profileModel = new ProfileModel();

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            // Format: Kecamatan, Desa/Kelurahan, Nama Kepala Desa/Lurah
            $kecamatan = trim($row[0]);
            $region = trim($row[1]);
            $name = trim($row[2]);

            if (empty($name) || $name === '-') {
                continue; // Skip if no name provided
            }

            $type = '';
            $position = '';
            $kelurahan = null;
            $desa = null;
            $institution = '';

            // Lurah
            if (strpos(strtolower($region), 'kelurahan') !== false) {
                $type = 'lurah';
                $regionClean = trim(str_ireplace('Kelurahan', '', $region));
                $position = 'Lurah ' . $regionClean;
                $kelurahan = $regionClean;
                $institution = 'Kecamatan ' . $kecamatan;
            } 
// Kepala Desa
elseif (strpos(strtolower($region), 'desa') !== false) {
    $type = 'kepala-desa';
    $regionClean = trim(str_ireplace('Desa', '', $region));
    $position = 'Kepala Desa ' . $regionClean;
    $desa = $regionClean;
    $institution = 'Desa ' . $regionClean;
} 
            } else {
                continue; // Unrecognized region type
            }

            // Generate a basic slug
            $slugBase = strtolower(url_title($position . ' ' . $name));
            $slug = $slugBase;
            $slugCounter = 1;
            while ($profileModel->where('slug', $slug)->first()) {
                $slug = $slugBase . '-' . $slugCounter;
                $slugCounter++;
            }

            $data = [
                'name'        => $name,
                'slug'        => $slug,
                'position'    => $position,
                'institution' => $institution,
                'type'        => $type,
                'kecamatan'   => $kecamatan,
                'kelurahan'   => $kelurahan,
                'desa'        => $desa,
                'order'       => 0,
            ];

            $profileModel->insert($data);
            $count++;
        }

        fclose($file);
        echo "Successfully imported {$count} profiles.\n";
    }
}
