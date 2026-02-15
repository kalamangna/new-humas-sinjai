<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // General
            [
                'key'   => 'site_name',
                'value' => 'Humas Sinjai',
                'group' => 'general',
                'type'  => 'text',
                'label' => 'Nama Situs',
            ],
            [
                'key'   => 'site_tagline',
                'value' => 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki',
                'group' => 'general',
                'type'  => 'text',
                'label' => 'Tagline Situs',
            ],
            [
                'key'   => 'site_logo',
                'value' => 'humas.png',
                'group' => 'general',
                'type'  => 'image',
                'label' => 'Logo Situs',
            ],
            [
                'key'   => 'developer_name',
                'value' => 'Diskominfo-SP Sinjai',
                'group' => 'general',
                'type'  => 'text',
                'label' => 'Nama Pengembang',
            ],

            // Contact
            [
                'key'   => 'contact_address',
                'value' => 'Jl. Persatuan Raya No. 101, Balangnipa, Sinjai Utara, Sinjai, Sulawesi Selatan 92612',
                'group' => 'contact',
                'type'  => 'textarea',
                'label' => 'Alamat Kantor',
            ],
            [
                'key'   => 'contact_email',
                'value' => 'humas@sinjaikab.go.id',
                'group' => 'contact',
                'type'  => 'text',
                'label' => 'Email Resmi',
            ],
            [
                'key'   => 'contact_map_embed',
                'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15914.868748882522!2d120.25206256860015!3d-5.12061324749323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db951010072b7a9%3A0x8WX2QetWMMaGdYiw5!2sBalangnipa%2C%20Sinjai%20Utara%2C%20Sinjai%20Regency%2C%20South%20Sulawesi!5e0!3m2!1sen!2sid!4v1715600000000!5m2!1sen!2sid',
                'group' => 'contact',
                'type'  => 'textarea',
                'label' => 'Embed Google Maps',
            ],
            [
                'key'   => 'contact_map_link',
                'value' => 'https://maps.app.goo.gl/8WX2QetWMMaGdYiw5',
                'group' => 'contact',
                'type'  => 'text',
                'label' => 'Link Navigasi Maps',
            ],

            // Social Media
            [
                'key'   => 'social_facebook',
                'value' => 'https://www.facebook.com/FP.KabupatenSinjai',
                'group' => 'social',
                'type'  => 'text',
                'label' => 'Facebook URL',
            ],
            [
                'key'   => 'social_instagram',
                'value' => 'https://www.instagram.com/sinjaikab',
                'group' => 'social',
                'type'  => 'text',
                'label' => 'Instagram URL',
            ],
            [
                'key'   => 'social_youtube',
                'value' => 'https://www.youtube.com/@SINJAITV',
                'group' => 'social',
                'type'  => 'text',
                'label' => 'YouTube URL',
            ],
            [
                'key'   => 'social_tiktok',
                'value' => 'https://www.tiktok.com/@pemkabsinjai',
                'group' => 'social',
                'type'  => 'text',
                'label' => 'TikTok URL',
            ],
            [
                'key'   => 'social_twitter',
                'value' => 'https://x.com/sinjaikab',
                'group' => 'social',
                'type'  => 'text',
                'label' => 'Twitter/X URL',
            ],

            // About Page
            [
                'key'   => 'about_vision',
                'value' => 'Menjadi pusat informasi yang terpercaya, transparan, dan akurat dalam mendukung pembangunan serta pelayanan publik di Kabupaten Sinjai.',
                'group' => 'about',
                'type'  => 'textarea',
                'label' => 'Visi Lembaga',
            ],
            [
                'key'   => 'about_mission',
                'value' => json_encode([
                    "Menyediakan informasi publik yang cepat, akurat, dan mudah diakses oleh masyarakat.",
                    "Memperkuat komunikasi dua arah antara pemerintah and masyarakat.",
                    "Mempromosikan potensi daerah dan keberhasilan pembangunan di Kabupaten Sinjai.",
                    "Meningkatkan citra positif pemerintah daerah melalui transparansi dan profesionalisme komunikasi publik."
                ]),
                'group' => 'about',
                'type'  => 'json',
                'label' => 'Misi Lembaga (JSON)',
            ],
            [
                'key'   => 'about_description_1',
                'value' => 'Bagian Hubungan Masyarakat (Humas) Pemerintah Kabupaten Sinjai merupakan unit kerja strategis yang bertanggung jawab dalam pengelolaan informasi dan komunikasi publik secara komprehensif. Kami hadir sebagai jembatan utama antara pemerintah dan masyarakat dalam menyampaikan berbagai informasi krusial seputar kebijakan, program strategis, serta realisasi kegiatan pembangunan di seluruh wilayah Kabupaten Sinjai.',
                'group' => 'about',
                'type'  => 'textarea',
                'label' => 'Deskripsi Profil 1',
            ],
            [
                'key'   => 'about_description_2',
                'value' => 'Melalui berbagai kanal komunikasi modern, mulai dari media sosial, portal web resmi, hingga kegiatan sosialisasi tatap muka, Humas Sinjai berkomitmen menjadi garda terdepan dalam menyebarluaskan informasi publik yang konstruktif dan inspiratif bagi seluruh lapisan masyarakat Sinjai demi terwujudnya tata kelola pemerintahan yang baik (Good Governance).',
                'group' => 'about',
                'type'  => 'textarea',
                'label' => 'Deskripsi Profil 2',
            ],
            [
                'key'   => 'about_tasks',
                'value' => json_encode([
                    ["Pengelolaan Informasi", "Mengumpulkan, mengolah, dan menyebarluaskan informasi resmi pemerintah."],
                    ["Media Relations", "Menjalin hubungan profesional dengan media massa nasional dan lokal."],
                    ["Publikasi Daerah", "Mempublikasikan berbagai kegiatan strategis dan prestasi pemerintah daerah."],
                    ["Dokumentasi Visual", "Mendokumentasikan seluruh kegiatan pemerintah melalui foto dan video profesional."],
                    ["Manajemen Isu", "Menangani isu publik dan menyampaikan klarifikasi resmi demi reputasi daerah."]
                ]),
                'group' => 'about',
                'type'  => 'json',
                'label' => 'Tugas Pokok & Fungsi (JSON)',
            ],
            [
                'key'   => 'about_teams',
                'value' => json_encode([
                    ["Kepala Bidang", "Memimpin dan mengkoordinasi seluruh kegiatan komunikasi publik.", "fa-user-tie"],
                    ["Staf Humas", "Melaksanakan tugas-tugas operasional dan layanan informasi publik.", "fa-users"],
                    ["Tim Dokumentasi", "Produksi konten visual profesional melalui foto dan video.", "fa-camera"]
                ]),
                'group' => 'about',
                'type'  => 'json',
                'label' => 'Struktur Organisasi (JSON)',
            ],
        ];

        foreach ($data as $item) {
            $item['created_at'] = date('Y-m-d H:i:s');
            $item['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('site_settings')->insert($item);
        }
    }
}