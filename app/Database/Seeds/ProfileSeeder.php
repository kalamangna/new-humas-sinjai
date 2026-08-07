<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\ProfileModel;

class ProfileSeeder extends Seeder
{
    public function run()
    {
        $profileModel = new ProfileModel();

        // Bersihkan SEMUA data profil yang ada sebelumnya agar tidak ganda
        $this->db->table('profiles')->truncate();
        echo "Tabel profiles berhasil dikosongkan.\n";

        $profiles = [
            // ============================
            // 1. PIMPINAN DAERAH (Ada Slug)
            // ============================
            [
                'name'        => 'Andi Seto Gadhista Asapa, S.H., LLM',
                'position'    => 'Bupati Sinjai',
                'institution' => 'Pemerintah Kabupaten Sinjai',
                'type'        => 'bupati',
                'order'       => 1,
                'has_slug'    => true
            ],
            [
                'name'        => 'Hj. Andi Kartini Ottong, S.P., M.SP.',
                'position'    => 'Wakil Bupati Sinjai',
                'institution' => 'Pemerintah Kabupaten Sinjai',
                'type'        => 'wakil-bupati',
                'order'       => 2,
                'has_slug'    => true
            ],
            [
                'name'        => 'Andi Jefrianto Asapa, S.Sos., M.Si.',
                'position'    => 'Sekretaris Daerah',
                'institution' => 'Sekretariat Daerah Kabupaten Sinjai',
                'type'        => 'sekda',
                'order'       => 3,
                'has_slug'    => true
            ],

            // ============================
            // 2. FORKOPIMDA (Urutan manual)
            // ============================
            [
                'name'        => 'AKBP Fery Nur Abdullah, S.I.K.',
                'position'    => 'Kapolres Sinjai',
                'institution' => 'Kepolisian Resor Sinjai',
                'type'        => 'forkopimda',
                'order'       => 1,
                'has_slug'    => false
            ],
            [
                'name'        => 'Letkol Inf. Sumardi, S.E., M.Si.',
                'position'    => 'Dandim 1424 Sinjai',
                'institution' => 'Komando Distrik Militer 1424/Sinjai',
                'type'        => 'forkopimda',
                'order'       => 2,
                'has_slug'    => false
            ],
            [
                'name'        => 'Zulkarnaen, S.H., M.H.',
                'position'    => 'Kepala Kejaksaan Negeri Sinjai',
                'institution' => 'Kejaksaan Negeri Sinjai',
                'type'        => 'forkopimda',
                'order'       => 3,
                'has_slug'    => false
            ],

            // ============================
            // 3. ESELON II (Urutan manual)
            // ============================
            [
                'name'        => 'Dr. Mansyur, S.Pd., M.Si.',
                'position'    => 'Kepala Dinas',
                'institution' => 'Dinas Komunikasi, Informatika dan Persandian',
                'type'        => 'eselon-ii',
                'order'       => 1,
                'has_slug'    => false
            ],
            [
                'name'        => 'dr. Emmy Kartahara Malik, MARS',
                'position'    => 'Kepala Dinas',
                'institution' => 'Dinas Kesehatan Kabupaten Sinjai',
                'type'        => 'eselon-ii',
                'order'       => 2,
                'has_slug'    => false
            ],

            // ============================
            // 4. ESELON III (Urutan manual)
            // ============================
            [
                'name'        => 'H. Sofwan Sabirin, S.Sos.',
                'position'    => 'Camat',
                'institution' => 'Kecamatan Sinjai Utara',
                'type'        => 'eselon-iii',
                'order'       => 1,
                'has_slug'    => false
            ],
            [
                'name'        => 'Andi Saoraja Arie Lesmana, S.STP',
                'position'    => 'Camat',
                'institution' => 'Kecamatan Sinjai Timur',
                'type'        => 'eselon-iii',
                'order'       => 2,
                'has_slug'    => false
            ],

            // ============================
            // 5. LURAH & KEPALA DESA
            // ============================
            [
                'name'        => 'Andi Mappiare, S.IP.',
                'position'    => 'Lurah',
                'institution' => 'Biringere',
                'type'        => 'lurah',
                'kecamatan'   => 'Sinjai Utara',
                'order'       => 0,
                'has_slug'    => false
            ],
            [
                'name'        => 'Budi Santoso, S.STP.',
                'position'    => 'Lurah',
                'institution' => 'Balangnipa',
                'type'        => 'lurah',
                'kecamatan'   => 'Sinjai Utara',
                'order'       => 0,
                'has_slug'    => false
            ],
            [
                'name'        => 'Kaharuddin, S.E.',
                'position'    => 'Kepala Desa',
                'institution' => 'Sanjai',
                'type'        => 'kepala-desa',
                'kecamatan'   => 'Sinjai Timur',
                'order'       => 0,
                'has_slug'    => false
            ],
            [
                'name'        => 'Samsul Bahri',
                'position'    => 'Kepala Desa',
                'institution' => 'Lasiai',
                'type'        => 'kepala-desa',
                'kecamatan'   => 'Sinjai Timur',
                'order'       => 0,
                'has_slug'    => false
            ]
        ];

        $count = 0;
        foreach ($profiles as $p) {
            // Slug, Bio, dan Gambar KHUSUS untuk 3 Pimpinan Utama
            $slug  = null;
            $bio   = null;
            $image = null;

            if ($p['has_slug']) {
                // Generate Slug
                $slugBase = strtolower(url_title($p['position'] . ' ' . $p['name'], '-', true));
                $slug = $slugBase;
                $slugCounter = 1;
                while ($profileModel->where('slug', $slug)->first()) {
                    $slug = $slugBase . '-' . $slugCounter;
                    $slugCounter++;
                }

                // Generate Bio
                $bio = "
                    <p><strong>" . $p['name'] . "</strong> saat ini menjabat sebagai <strong>" . $p['position'] . "</strong> di <strong>" . $p['institution'] . "</strong>" . (!empty($p['kecamatan']) ? " (" . $p['kecamatan'] . ")" : "") . ". Beliau dikenal sebagai sosok yang berdedikasi tinggi dalam melayani masyarakat Kabupaten Sinjai.</p>
                    <p>Kariernya di pemerintahan dibangun melalui rekam jejak yang solid, pengalaman kepemimpinan yang matang, serta komitmen kuat untuk memajukan pembangunan daerah. Fokus utamanya mencakup peningkatan kualitas pelayanan publik, penguatan transparansi birokrasi, serta pemberdayaan ekonomi masyarakat lokal yang berkelanjutan.</p>
                    <p>Dalam menjalankan amanahnya, beliau selalu mengedepankan prinsip <em>Siri' Na Pacce</em> yang menjadi filosofi luhur masyarakat Bugis-Makassar, serta proaktif merangkul berbagai elemen masyarakat demi terciptanya Kabupaten Sinjai yang lebih sejahtera dan berdaya saing tinggi.</p>
                ";

                // Generate Foto Elegan (Professional Portraits)
                if ($p['type'] === 'bupati') {
                    // Pria berjas wibawa
                    $image = "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&q=80&w=800";
                } elseif ($p['type'] === 'wakil-bupati') {
                    // Wanita profesional
                    $image = "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=800";
                } elseif ($p['type'] === 'sekda') {
                    // Pria berjas elegan lainnya
                    $image = "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=800";
                } else {
                    $hash = md5($p['name']);
                    $image = "https://ui-avatars.com/api/?name=" . urlencode($p['name']) . "&background=0D8ABC&color=fff&size=512";
                }
            }

            $data = [
                'name'        => $p['name'],
                'slug'        => $slug,
                'position'    => $p['position'],
                'institution' => $p['institution'],
                'type'        => $p['type'],
                'kecamatan'   => $p['kecamatan'] ?? null,
                'order'       => $p['order'] ?? 0,
                'bio'         => $bio,
                'image'       => $image
            ];

            $profileModel->insert($data);
            $count++;
        }

        echo "Berhasil menyuntikkan {$count} profil lengkap (dengan foto dan biografi)!\n";
    }
}
