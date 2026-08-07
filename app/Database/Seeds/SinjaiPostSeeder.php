<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\PostCategoryModel;
use App\Models\UserModel;

class SinjaiPostSeeder extends Seeder
{
    public function run()
    {
        $postModel = new PostModel();
        $categoryModel = new CategoryModel();
        $postCategoryModel = new PostCategoryModel();
        $userModel = new UserModel();

        // Truncate tables to wipe out old dummy data
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('posts')->truncate();
        $this->db->table('post_categories')->truncate();
        $this->db->table('post_tags')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "Tabel posts, post_categories, dan post_tags berhasil dikosongkan.\n";

        // Get all categories to assign randomly
        $categories = $categoryModel->findAll();
        $users = $userModel->findAll();
        $tags = $this->db->table('tags')->get()->getResultArray();

        if (empty($users)) {
            echo "Error: Belum ada user admin. Buat user terlebih dahulu.\n";
            return;
        }

        if (empty($categories)) {
            echo "Error: Belum ada kategori berita.\n";
            return;
        }

        if (empty($tags)) {
            echo "Error: Belum ada tag berita. Jalankan TagSeeder terlebih dahulu.\n";
            return;
        }

        // Realistik Sinjai Titles
        $sinjaiTitles = [
            "Bupati Sinjai Resmi Membuka Musrenbang RKPD Tingkat Kabupaten Tahun 2026",
            "Pemkab Sinjai Raih Penghargaan Opini WTP ke-10 dari BPK RI",
            "Dinas PUPR Terus Genjot Perbaikan Jalan Poros Sinjai - Bulukumba Jelang Idul Fitri",
            "Penyaluran Bantuan BLT-DD Tahap II di Desa Sanjai Berjalan Aman dan Lancar",
            "Dinas Kesehatan Sinjai Gencarkan Penyuluhan Pencegahan Stunting di Kecamatan Tellu Limpoe",
            "Rapat Paripurna DPRD Sinjai Bahas LKPJ Bupati Tahun Anggaran 2025",
            "Festival Budaya Karampuang Sukses Sedot Ribuan Wisatawan Lokal",
            "Harga Kebutuhan Pokok di Pasar Sentral Sinjai Terpantau Stabil Jelang Nataru",
            "Pj Bupati Sinjai Tinjau Langsung Lokasi Terdampak Banjir di Kecamatan Sinjai Utara",
            "Dinas Pertanian Bagikan Ribuan Bibit Kopi Gratis untuk Petani Sinjai Borong",
            "Sidak ASN Pasca Libur Lebaran, Sekda Sinjai Pastikan Pelayanan Publik Berjalan Normal",
            "Gelar Rakor Inflasi, Pemkab Sinjai Ambil Langkah Strategis Tekan Kenaikan Harga",
            "Program Padat Karya Tunai Terus Berjalan, Serap Puluhan Tenaga Kerja Lokal di Sinjai Timur",
            "Polres Sinjai dan Pemkab Sinergi Ciptakan Pilkada Damai dan Kondusif",
            "Lomba Perahu Tradisional di Lappa Meriahkan Peringatan Hari Jadi Sinjai ke-462"
        ];

        $dummyText = "Pemerintah Kabupaten Sinjai terus berupaya memberikan pelayanan terbaik bagi masyarakat. Dalam kegiatan yang dilaksanakan hari ini, berbagai pemangku kepentingan hadir untuk memastikan kelancaran program strategis daerah. Ke depan, diharapkan sinergi antara pemerintah, swasta, dan masyarakat dapat semakin kuat demi mewujudkan visi Sinjai yang sejahtera dan berdaya saing tinggi.";

        foreach ($sinjaiTitles as $index => $title) {
            // Generate dummy HTML content contextually
            $content = "
                <p><strong>SINJAI</strong> &mdash; $dummyText</p>
                <p>Menurut pantauan di lapangan, antusiasme warga sangat tinggi. Hal ini membuktikan bahwa program pemerintah telah menyentuh lapisan akar rumput dengan efektif.</p>
                <p>\"Kami berkomitmen penuh untuk terus mendengarkan aspirasi masyarakat Sinjai dan mengeksekusinya dalam bentuk kebijakan nyata,\" ungkap salah satu pejabat yang hadir.</p>
            ";

            $randomUser = $users[array_rand($users)];
            
            // Generate a random date within the last 3 months
            $timestamp = time() - rand(0, 90 * 24 * 60 * 60);
            $publishedAt = date('Y-m-d H:i:s', $timestamp);

            $postData = [
                'title' => $title,
                'slug' => url_title($title, '-', true) . '-' . rand(100, 999),
                'content' => $content,
                'status' => 'published',
                'user_id' => $randomUser['id'],
                'published_at' => $publishedAt,
                'thumbnail' => 'https://picsum.photos/seed/' . md5($title) . '/800/600',
            ];

            $postId = $postModel->insert($postData);

            if ($postId) {
                // Assign 1-2 random categories
                $catCount = rand(1, 2);
                $randomCategoryKeys = (array) array_rand($categories, $catCount);
                foreach ($randomCategoryKeys as $key) {
                    $cat = $categories[$key];
                    $postCategoryModel->insert([
                        'post_id' => $postId,
                        'category_id' => $cat['id'],
                    ]);
                }

                // Assign 2-3 random tags
                $tagCount = rand(2, 3);
                $randomTagKeys = (array) array_rand($tags, $tagCount);
                foreach ($randomTagKeys as $key) {
                    $tag = $tags[$key];
                    $this->db->table('post_tags')->insert([
                        'post_id' => $postId,
                        'tag_id' => $tag['id'],
                    ]);
                }
            }
        }

        echo "Berhasil membuat " . count($sinjaiTitles) . " berita khusus konteks Kabupaten Sinjai!\n";
    }
}
