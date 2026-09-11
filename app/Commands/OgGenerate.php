<?php

namespace App\Commands;

use App\Models\PostModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class OgGenerate extends BaseCommand
{
    protected $group       = 'SEO';
    protected $name        = 'og:generate';
    protected $description = 'Generate gambar Open Graph (1200x630, <200KB) untuk semua berita yang belum memilikinya.';
    protected $usage       = 'og:generate [--force] [--limit <n>]';
    protected $arguments   = [];
    protected $options     = [
        '--force' => 'Paksa generate ulang meskipun gambar OG sudah ada.',
        '--limit' => 'Batasi jumlah berita yang diproses.',
    ];

    public function run(array $params)
    {
        helper(['image']);

        $force = array_key_exists('force', $params) || CLI::getOption('force');
        $limit = (int) ($params['limit'] ?? CLI::getOption('limit') ?? 0);

        $postModel = new PostModel();
        $builder = $postModel->where('status', 'published')
            ->where('thumbnail IS NOT NULL')
            ->where('thumbnail !=', '')
            ->orderBy('published_at', 'DESC');

        if ($limit > 0) {
            $builder->limit($limit);
        }

        $posts = $builder->findAll();
        $total = count($posts);

        if ($total === 0) {
            CLI::write('Tidak ada berita yang memenuhi kriteria.', 'yellow');
            return;
        }

        CLI::write("Memproses {$total} berita untuk pembuatan gambar Open Graph...", 'yellow');

        $generated = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($posts as $post) {
            $slug = $post['slug'] ?? '';
            if (empty($slug)) {
                $skipped++;
                continue;
            }

            $targetOg = FCPATH . 'uploads/og/' . $slug . '.jpg';

            // Lewati jika sudah ada dan tidak dipaksa (--force)
            if (file_exists($targetOg) && !$force) {
                $skipped++;
                continue;
            }

            $tempCreated = null;
            $sourcePath = $this->resolveLocalFile($post['thumbnail'], $tempCreated);

            if (!$sourcePath) {
                $failed++;
                continue;
            }

            if (generateOgImage($sourcePath, $targetOg)) {
                $generated++;
            } else {
                $failed++;
            }

            if ($tempCreated && file_exists($tempCreated)) {
                @unlink($tempCreated);
            }
        }

        CLI::write("Selesai!", 'green');
        CLI::write("- Berhasil di-generate: {$generated}", 'green');
        CLI::write("- Dilewati (sudah ada): {$skipped}", 'cyan');
        if ($failed > 0) {
            CLI::write("- Gagal / gambar sumber tidak dapat diakses: {$failed}", 'red');
        }
    }

    private function resolveLocalFile(?string $filename, ?string &$tempCreated = null): ?string
    {
        if (empty($filename)) return null;

        // Jika URL eksternal (seperti dummy seed picsum/placeholder), unduh ke berkas sementara
        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'follow_location' => 1,
                    'user_agent' => 'Mozilla/5.0 (compatible; HumasSinjaiBot/1.0)'
                ]
            ]);
            $content = @file_get_contents($filename, false, $context);
            if ($content) {
                $temp = WRITEPATH . 'cache/' . uniqid('og_download_') . '.tmp';
                if (!is_dir(dirname($temp))) {
                    mkdir(dirname($temp), 0755, true);
                }
                file_put_contents($temp, $content);
                $tempCreated = $temp;
                return $temp;
            }
            return null;
        }

        $cleanPath = preg_replace('/^uploads\/(thumbnails|posts|og)\//', '', ltrim($filename, '/'));
        $basename  = pathinfo($cleanPath, PATHINFO_BASENAME);

        $roots = [
            'uploads/posts/',
            'uploads/thumbnails/',
            'uploads/',
        ];

        foreach ($roots as $root) {
            if (is_file(FCPATH . $root . $cleanPath)) {
                return FCPATH . $root . $cleanPath;
            }
            if (is_file(FCPATH . $root . $basename)) {
                return FCPATH . $root . $basename;
            }
        }

        // Cek langsung jika full path relatif diberikan
        if (is_file(FCPATH . ltrim($filename, '/'))) {
            return FCPATH . ltrim($filename, '/');
        }

        return null;
    }
}
