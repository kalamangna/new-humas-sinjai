<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\TagModel;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tagModel = new TagModel();

        // Matikan Foreign Key check agar bisa truncate tabel tags
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('tags')->truncate();
        $this->db->table('post_tags')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "Tabel tags dan post_tags berhasil dikosongkan.\n";

        // Tag Relevan dengan Kabupaten Sinjai
        $sinjaiTags = [
            'Sinjai Hebat',
            'Info Sinjai',
            'Pembangunan',
            'Bantuan Sosial',
            'Pilkada Damai',
            'Pariwisata',
            'Pendidikan',
            'Pelayanan Publik',
            'Pertanian',
            'Kesehatan Masyarakat',
            'WTP',
            'Karampuang',
            'Bupati Sinjai',
            'Sekda Sinjai',
            'HUT Sinjai'
        ];

        foreach ($sinjaiTags as $tagName) {
            $tagModel->insert([
                'name' => $tagName,
                'slug' => strtolower(url_title($tagName, '-', true)),
            ]);
        }

        echo "Berhasil membuat " . count($sinjaiTags) . " tag berita kontekstual!\n";
    }
}