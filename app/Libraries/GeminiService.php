<?php

namespace App\Libraries;

use Config\Services;
use Exception;

class GeminiService
{
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';
    private const MAX_CONTENT_LENGTH = 500;

    /**
     * Chain prioritas model — dicoba berurutan dari atas ke bawah.
     * Jika semua gagal, fallback ke tag hardcoded.
     */
    private const MODEL_CHAIN = [
        'gemini-3.5-flash-lite',    // Terbaru & cepat (~1 detik)
        'gemini-flash-lite-latest', // Alias otomatis versi Lite terbaru
        'gemini-2.5-flash-lite',    // Fallback Lite stabil
        'gemini-2.5-flash',         // Fallback penuh stabil
    ];

    private const MAX_SUGGESTED_TAGS = 5;

    private const GENERIC_TAG_BLOCKLIST = [
        'sinjai',
        'berita sinjai',
        'kabupaten sinjai',
        'pemkab sinjai',
        'pemerintah sinjai',
        'pemerintah kabupaten sinjai',
        'pemerintah daerah',
        'berita daerah',
        'berita daerah sinjai',
        'sinjai hari ini',
        'humas sinjai',
        'berita',
        'daerah',
        'informasi',
        'informasi publik',
    ];

    private const HARDCODED_FALLBACK = [
        'Sulawesi Selatan', 'Diskominfo Sinjai', 'Bupati Sinjai', 'Pelayanan Publik', 'Pembangunan Daerah',
    ];

    private string $apiKey;
    private $httpClient;

    public function __construct(?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? env('GEMINI_API_KEY') ?? '';
        $this->httpClient = Services::curlrequest();
    }

    public function suggestTags(string $title, string $content): array
    {
        if (!$this->validateApiKey()) {
            return [];
        }

        $prompt = $this->buildPrompt($title, $this->prepareContent($content));

        foreach (self::MODEL_CHAIN as $index => $model) {
            try {
                $tagsString = $this->requestPrompt($prompt, $model);
                $tags = $this->parseTags($tagsString);
                if ($index > 0) {
                    log_message('info', "[GeminiService] Succeeded with fallback model: {$model}.");
                }
                return $tags;
            } catch (Exception $e) {
                $next = self::MODEL_CHAIN[$index + 1] ?? 'hardcoded fallback';
                log_message('warning', "[GeminiService] Model {$model} failed: {$e->getMessage()}. Trying: {$next}.");
            }
        }

        log_message('error', '[GeminiService] All models failed. Returning hardcoded fallback tags.');
        return self::HARDCODED_FALLBACK;
    }

    /**
     * Sarankan ID sub-kategori paling relevan berdasarkan daftar sub-kategori resmi.
     *
     * @param array<int, array{id: int|string, name: string, parent_name: string}> $subCategories
     * @return int[]
     */
    public function suggestCategories(string $title, string $content, array $subCategories, int $maxCategories = 3): array
    {
        if (!$this->validateApiKey() || empty($subCategories)) {
            return [];
        }

        $validIds = [];
        $categoryLines = [];
        foreach ($subCategories as $sub) {
            $id = (int) ($sub['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $validIds[$id] = true;
            $parentName = trim((string) ($sub['parent_name'] ?? ''));
            $name = trim((string) ($sub['name'] ?? ''));
            $categoryLines[] = $parentName !== '' ? "{$id}: {$parentName} > {$name}" : "{$id}: {$name}";
        }

        if (empty($validIds)) {
            return [];
        }

        $prompt = $this->buildCategoryPrompt(
            $title,
            $this->prepareContent($content, 800),
            implode("\n", $categoryLines),
            max(1, $maxCategories)
        );

        foreach (self::MODEL_CHAIN as $index => $model) {
            try {
                $rawResponse = $this->requestPrompt($prompt, $model);
                $ids = $this->parseCategoryIds($rawResponse, $validIds, max(1, $maxCategories));
                if (!empty($ids)) {
                    if ($index > 0) {
                        log_message('info', "[GeminiService] Category suggestion succeeded with fallback model: {$model}.");
                    }
                    return $ids;
                }
                throw new Exception('No valid category IDs returned by model');
            } catch (Exception $e) {
                $next = self::MODEL_CHAIN[$index + 1] ?? 'none';
                log_message('warning', "[GeminiService] Category model {$model} failed: {$e->getMessage()}. Trying: {$next}.");
            }
        }

        log_message('error', '[GeminiService] All models failed for category suggestion.');
        return [];
    }

    private function requestPrompt(string $prompt, string $model): string
    {
        $url = $this->buildApiUrl($model);
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        // Exceptions bubble up agar caller mencoba model berikutnya dalam chain.
        $response = $this->httpClient->post($url, [
            'json'        => $payload,
            'timeout'     => 10,
            'http_errors' => true,
        ]);

        $decoded = json_decode($response->getBody(), true) ?? [];
        return $this->extractTagsFromResponse($decoded);
    }

    private function validateApiKey(): bool
    {
        if (empty($this->apiKey)) {
            log_message('error', '[GeminiService] GEMINI_API_KEY is not set.');
            return false;
        }
        return true;
    }

    private function buildApiUrl(string $model): string
    {
        return self::BASE_URL . $model . ':generateContent?key=' . $this->apiKey;
    }

    private function prepareContent(string $content, int $maxLength = self::MAX_CONTENT_LENGTH): string
    {
        return mb_substr(strip_tags($content), 0, $maxLength, 'UTF-8');
    }

    private function buildCategoryPrompt(string $title, string $content, string $categoryList, int $maxCategories): string
    {
        return sprintf(
            "Berdasarkan judul dan konten berita pemerintah daerah di bawah ini, pilih 1 sampai %d ID sub-kategori yang paling relevan HANYA dari daftar resmi berikut.

Aturan:
- Kembalikan HANYA angka ID dipisahkan koma, contoh: 7, 31, 328
- Jangan berikan penjelasan, nama kategori, bullet, atau teks lain.
- Jangan memaksakan %d kategori jika berita hanya relevan dengan 1 atau 2 kategori.
- Pilih kategori pejabat (misal Bupati, Wakil Bupati, Sekretaris Daerah, Staf Ahli Bupati) hanya jika pejabat tersebut benar-benar hadir atau menjadi subjek berita, bukan sekadar diwakili.
- Bedakan Layanan Publik (pelayanan operasional langsung ke warga) dan Program Prioritas (kebijakan atau agenda program prioritas daerah).

Daftar Sub-Kategori Resmi:
%s

Judul: \"%s\"
Konten: \"%s\"",
            $maxCategories,
            $maxCategories,
            $categoryList,
            $title,
            $content
        );
    }

    /**
     * @param array<int, bool> $validIds
     * @return int[]
     */
    private function parseCategoryIds(string $rawResponse, array $validIds, int $maxCategories): array
    {
        preg_match_all('/\d+/', $rawResponse, $matches);
        $ids = [];
        foreach ($matches[0] ?? [] as $num) {
            $id = (int) $num;
            if (isset($validIds[$id]) && !in_array($id, $ids, true)) {
                $ids[] = $id;
                if (count($ids) >= $maxCategories) {
                    break;
                }
            }
        }
        return $ids;
    }

    private function buildPrompt(string $title, string $content): string
    {
        return sprintf(
            "Berdasarkan judul dan konten berita pemerintah daerah di bawah ini, hasilkan tepat %d tag SEO singkat, spesifik, dan relevan.

Aturan:
- Tampilkan hanya daftar tag dipisahkan koma, tanpa penjelasan, nomor, bullet, atau tanda pagar (#).
- Gunakan format Title Case (huruf kapital di awal kata penting) dengan panjang 1 sampai 3 kata per tag.
- Prioritaskan entitas yang dapat dipakai ulang pada berita lain: nama OPD atau lembaga, nama program, lokasi kecamatan atau desa, dan topik inti.
- DILARANG menggunakan tag generik situs seperti: Sinjai, Berita Sinjai, Kabupaten Sinjai, Pemkab Sinjai, Pemerintah Daerah, Berita Daerah, Sinjai Hari Ini, Berita, Daerah, Informasi.
- DILARANG mengulang konsep yang sama dengan variasi singkatan dan kepanjangan dalam satu daftar (pilih salah satu bentuk yang paling umum, misalnya cukup 'Kejari Sinjai' atau 'Makan Bergizi Gratis', jangan keduanya).
- DILARANG menghasilkan tag berupa angka saja.

Judul: \"%s\"
Konten: \"%s\"",
            self::MAX_SUGGESTED_TAGS,
            $title,
            $content
        );
    }

    private function extractTagsFromResponse(array $response): string
    {
        if (empty($response['candidates'][0]['content']['parts'][0]['text'])) {
            log_message('warning', '[GeminiService] Empty or unexpected response: ' . json_encode($response));
            throw new Exception('Invalid API response format or blocked content');
        }

        return $response['candidates'][0]['content']['parts'][0]['text'];
    }

    private function parseTags(string $tagsString): array
    {
        $rawTags = explode(',', $tagsString);
        $cleanTags = [];
        $seen = [];

        foreach ($rawTags as $raw) {
            $tag = trim(ltrim(trim($raw), '#-*• '));
            $tag = preg_replace('/\s+/', ' ', $tag);
            if ($tag === '' || mb_strlen($tag, 'UTF-8') < 2 || ctype_digit($tag)) {
                continue;
            }

            $normalized = mb_strtolower($tag, 'UTF-8');
            if (in_array($normalized, self::GENERIC_TAG_BLOCKLIST, true) || isset($seen[$normalized])) {
                continue;
            }

            $seen[$normalized] = true;
            $cleanTags[] = $tag;

            if (count($cleanTags) >= self::MAX_SUGGESTED_TAGS) {
                break;
            }
        }

        return $cleanTags;
    }
}