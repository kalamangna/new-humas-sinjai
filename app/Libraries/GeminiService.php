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
    private const PROMPT_CANDIDATE_TAGS = 10;

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
        'pemerintah',
        'pemerintahan',
        'berita terkini',
        'berita terkini sinjai',
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

    public function suggestTags(string $title, string $content, array $existingTags = []): array
    {
        if (!$this->validateApiKey()) {
            return [];
        }

        $preparedContent = $this->prepareContent($content, 1000);
        $prompt = $this->buildPrompt($title, $preparedContent, $existingTags);

        foreach (self::MODEL_CHAIN as $index => $model) {
            try {
                $tagsString = $this->requestPrompt($prompt, $model);
                $tags = $this->parseTags($tagsString, $existingTags);
                if (count($tags) < self::MAX_SUGGESTED_TAGS) {
                    $tags = $this->padSuggestedTags($tags, $title, $content, $existingTags);
                }
                if (count($tags) < self::MAX_SUGGESTED_TAGS) {
                    $missing = self::MAX_SUGGESTED_TAGS - count($tags);
                    $topUpPrompt = $this->buildTopUpPrompt($title, $preparedContent, $tags, $missing + 3, $existingTags);
                    try {
                        $extraTagsString = $this->requestPrompt($topUpPrompt, $model);
                        $extraTags = $this->parseTags($extraTagsString, $existingTags, $tags, $missing);
                        if (!empty($extraTags)) {
                            $tags = array_merge($tags, $extraTags);
                        }
                    } catch (Exception $topUpError) {
                        log_message('warning', "[GeminiService] Tag top-up with {$model} failed: {$topUpError->getMessage()}");
                    }
                }
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

    /**
     * Evaluasi kandidat tag duplikat secara semantik menggunakan Gemini.
     *
     * @param array<int, array{id: int, name: string, post_count: int}> $tags
     * @return array<int, array{source_id: int, target_id: int, reason: string}>
     */
    public function suggestTagMerges(array $tags, int $maxSuggestions = 25): array
    {
        if (!$this->validateApiKey() || count($tags) < 2) {
            return [];
        }

        $validIds = [];
        $lines = [];
        foreach ($tags as $tag) {
            $id = (int) ($tag['id'] ?? 0);
            $name = trim((string) ($tag['name'] ?? ''));
            $count = (int) ($tag['post_count'] ?? 0);
            if ($id <= 0 || $name === '') {
                continue;
            }
            $validIds[$id] = true;
            $lines[] = "{$id}: {$name} ({$count} berita)";
        }

        if (count($validIds) < 2) {
            return [];
        }

        $prompt = $this->buildTagMergePrompt(implode("\n", $lines), max(1, $maxSuggestions));

        foreach (self::MODEL_CHAIN as $index => $model) {
            try {
                $rawResponse = $this->requestPrompt($prompt, $model, 15);
                $suggestions = $this->parseTagMergeSuggestions($rawResponse, $validIds, max(1, $maxSuggestions));
                if ($index > 0) {
                    log_message('info', "[GeminiService] Tag merge suggestion succeeded with fallback model: {$model}.");
                }
                return $suggestions;
            } catch (Exception $e) {
                $next = self::MODEL_CHAIN[$index + 1] ?? 'none';
                log_message('warning', "[GeminiService] Tag merge model {$model} failed: {$e->getMessage()}. Trying: {$next}.");
            }
        }

        log_message('error', '[GeminiService] All models failed for tag merge suggestion.');
        return [];
    }

    private function requestPrompt(string $prompt, string $model, int $timeout = 10): string
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
            'timeout'     => $timeout,
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

    private function buildTagMergePrompt(string $tagList, int $maxSuggestions): string
    {
        return sprintf(
            "Analisis daftar tag berita pemerintah daerah di bawah ini dan temukan maksimal %d pasangan tag yang benar-benar merupakan entitas atau konsep yang 100%% sama (misalnya typo, variasi ejaan, singkatan dan kepanjangannya, atau variasi nama pejabat dan OPD yang sama).

Aturan ketat:
- Pilih target_id sebagai tag dengan ejaan paling baku dan jumlah berita lebih banyak, serta source_id sebagai tag duplikat yang akan digabungkan ke target_id.
- DILARANG menggabungkan entitas yang berbeda meskipun mirip huruf atau masih satu bidang, misalnya: Bappeda Sinjai != Bapenda Sinjai, SMAN 1 Sinjai != SMKN 1 Sinjai, Kemenag Sinjai != Kemenhaj Sinjai, Pemprov Sulsel != Porprov Sulsel, jabatan berbeda (Bupati != Wakil Bupati), atau tahun berbeda.
- Kembalikan HANYA JSON array valid tanpa teks tambahan, dengan format:
  [{\"source_id\": 12, \"target_id\": 5, \"reason\": \"Singkatan OPD yang sama\"}]
- Jika tidak ada pasangan yang 100%% duplikat, kembalikan [].

Daftar Tag:
%s",
            $maxSuggestions,
            $tagList
        );
    }

    /**
     * @param array<int, bool> $validIds
     * @return array<int, array{source_id: int, target_id: int, reason: string}>
     */
    private function parseTagMergeSuggestions(string $rawResponse, array $validIds, int $maxSuggestions): array
    {
        $cleaned = trim($rawResponse);
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/i', $cleaned, $matches)) {
            $cleaned = trim($matches[1]);
        }

        $decoded = json_decode($cleaned, true);
        if (!is_array($decoded)) {
            if (preg_match('/\[[\s\S]*\]/', $cleaned, $arrayMatch)) {
                $decoded = json_decode($arrayMatch[0], true);
            }
        }

        if (!is_array($decoded)) {
            throw new Exception('Invalid JSON returned by model for tag merge suggestions');
        }

        $suggestions = [];
        $usedSources = [];
        $usedTargets = [];

        foreach ($decoded as $item) {
            if (!is_array($item)) {
                continue;
            }
            $sourceId = (int) ($item['source_id'] ?? 0);
            $targetId = (int) ($item['target_id'] ?? 0);
            $reason = trim((string) ($item['reason'] ?? ''));

            if (
                $sourceId <= 0
                || $targetId <= 0
                || $sourceId === $targetId
                || !isset($validIds[$sourceId], $validIds[$targetId])
                || isset($usedSources[$sourceId])
                || isset($usedTargets[$sourceId])
                || isset($usedSources[$targetId])
            ) {
                continue;
            }

            $usedSources[$sourceId] = true;
            $usedTargets[$targetId] = true;
            $suggestions[] = [
                'source_id' => $sourceId,
                'target_id' => $targetId,
                'reason'    => $reason !== '' ? mb_substr($reason, 0, 120, 'UTF-8') : 'Duplikat semantik',
            ];

            if (count($suggestions) >= $maxSuggestions) {
                break;
            }
        }

        return $suggestions;
    }

    private function buildExistingTagsSection(array $existingTags): string
    {
        $cleanExisting = array_values(array_filter(
            array_map(static fn ($tag) => trim((string) $tag), $existingTags),
            static fn ($tag) => $tag !== '' && !in_array(mb_strtolower($tag, 'UTF-8'), self::GENERIC_TAG_BLOCKLIST, true)
        ));

        if (empty($cleanExisting)) {
            return '';
        }

        return "\n- PRIORITASKAN menggunakan tag yang sudah ada pada Daftar Tag Eksisting di bawah dengan ejaan persis sama jika topiknya relevan. DILARANG membuat variasi ejaan/singkatan baru jika konsepnya sudah ada pada daftar tersebut.\n- Setiap tag WAJIB relevan secara langsung dengan judul atau isi berita. DILARANG memilih tag dari Daftar Tag Eksisting apabila tokoh, OPD, lokasi, atau topiknya tidak disebut atau dibahas dalam berita.\n\nDaftar Tag Eksisting:\n"
            . implode(', ', $cleanExisting);
    }

    private function buildPrompt(string $title, string $content, array $existingTags = []): string
    {
        $existingSection = $this->buildExistingTagsSection($existingTags);

        return sprintf(
            "Berdasarkan judul dan konten berita pemerintah daerah di bawah ini, hasilkan %d kandidat tag SEO singkat, spesifik, dan paling relevan (urutkan dari yang paling relevan).

Aturan:
- Tampilkan hanya daftar tag dipisahkan koma, tanpa penjelasan, nomor, bullet, atau tanda pagar (#).
- Gunakan format Title Case (huruf kapital di awal kata penting) dengan panjang 1 sampai 3 kata per tag.
- Prioritaskan entitas yang dapat dipakai ulang pada berita lain: nama OPD atau lembaga, nama program, lokasi kecamatan atau desa, dan topik inti.
- DILARANG menggunakan tag generik situs seperti: Sinjai, Berita Sinjai, Kabupaten Sinjai, Pemkab Sinjai, Pemerintah Daerah, Berita Daerah, Sinjai Hari Ini, Berita, Daerah, Informasi, Pemerintah, Pemerintahan.
- DILARANG mengulang konsep yang sama dengan variasi singkatan dan kepanjangan dalam satu daftar (pilih salah satu bentuk yang paling umum, misalnya cukup 'Kejari Sinjai' atau 'Makan Bergizi Gratis', jangan keduanya).
- DILARANG menghasilkan tag berupa angka saja.%s

Judul: \"%s\"
Konten: \"%s\"",
            self::PROMPT_CANDIDATE_TAGS,
            $existingSection,
            $title,
            $content
        );
    }

    /**
     * Bangun prompt tambahan untuk melengkapi kekurangan tag agar tetap relevan dan tidak mengulang tag yang sudah ada.
     *
     * @param string[] $currentTags
     * @param string[] $existingTags
     */
    private function buildTopUpPrompt(string $title, string $content, array $currentTags, int $candidateCount, array $existingTags = []): string
    {
        $existingSection = $this->buildExistingTagsSection($existingTags);
        $currentList = implode(', ', $currentTags);

        return sprintf(
            "Berdasarkan judul dan konten berita pemerintah daerah di bawah ini, hasilkan %d kandidat tag SEO tambahan yang singkat, spesifik, dan relevan.

Aturan:
- Tampilkan hanya daftar tag dipisahkan koma, tanpa penjelasan, nomor, bullet, atau tanda pagar (#).
- Gunakan format Title Case dengan panjang 1 sampai 3 kata per tag.
- DILARANG mengulang atau membuat variasi dari tag yang sudah terpilih berikut: %s
- DILARANG menggunakan tag generik situs seperti: Sinjai, Berita Sinjai, Kabupaten Sinjai, Pemkab Sinjai, Pemerintah Daerah, Berita Daerah, Sinjai Hari Ini, Berita, Daerah, Informasi, Pemerintah, Pemerintahan.
- DILARANG menghasilkan tag berupa angka saja.%s

Judul: \"%s\"
Konten: \"%s\"",
            $candidateCount,
            $currentList,
            $existingSection,
            $title,
            $content
        );
    }

    /**
     * Lengkapi jumlah tag jika hasil parsing AI kurang dari MAX_SUGGESTED_TAGS
     * menggunakan tag eksisting yang relevan dengan judul/konten lalu fallback standar.
     *
     * @param string[] $tags
     * @param string[] $existingTags
     * @return string[]
     */
    private function padSuggestedTags(array $tags, string $title, string $content, array $existingTags): array
    {
        $seenLower = [];
        $seenAlnum = [];
        foreach ($tags as $tag) {
            $lower = mb_strtolower($tag, 'UTF-8');
            $alnum = preg_replace('/[^a-z0-9]/u', '', $lower) ?? '';
            $seenLower[$lower] = true;
            if ($alnum !== '') {
                $seenAlnum[$alnum] = true;
            }
        }

        $haystack = mb_strtolower($title . ' ' . strip_tags($content), 'UTF-8');
        $matchedExisting = [];

        foreach ($existingTags as $candidate) {
            $name = trim((string) $candidate);
            if ($name === '') {
                continue;
            }
            $lower = mb_strtolower($name, 'UTF-8');
            $alnum = preg_replace('/[^a-z0-9]/u', '', $lower) ?? '';
            if (
                in_array($lower, self::GENERIC_TAG_BLOCKLIST, true)
                || isset($seenLower[$lower])
                || ($alnum !== '' && isset($seenAlnum[$alnum]))
            ) {
                continue;
            }

            $pattern = '/(?<![\p{L}\p{N}])' . preg_quote($lower, '/') . '(?![\p{L}\p{N}])/u';
            if (preg_match($pattern, $haystack)) {
                $matchedExisting[] = $name;
            }
        }

        foreach ($matchedExisting as $candidate) {
            if (count($tags) >= self::MAX_SUGGESTED_TAGS) {
                break;
            }
            $lower = mb_strtolower($candidate, 'UTF-8');
            $alnum = preg_replace('/[^a-z0-9]/u', '', $lower) ?? '';
            if (
                isset($seenLower[$lower])
                || ($alnum !== '' && isset($seenAlnum[$alnum]))
            ) {
                continue;
            }
            $seenLower[$lower] = true;
            if ($alnum !== '') {
                $seenAlnum[$alnum] = true;
            }
            $tags[] = $candidate;
        }

        return $tags;
    }

    private function extractTagsFromResponse(array $response): string
    {
        if (empty($response['candidates'][0]['content']['parts'][0]['text'])) {
            log_message('warning', '[GeminiService] Empty or unexpected response: ' . json_encode($response));
            throw new Exception('Invalid API response format or blocked content');
        }

        return $response['candidates'][0]['content']['parts'][0]['text'];
    }

    private function parseTags(
        string $tagsString,
        array $existingTags = [],
        array $excludeTags = [],
        int $limit = self::MAX_SUGGESTED_TAGS
    ): array {
        $canonicalByLower = [];
        $canonicalByAlnum = [];
        foreach ($existingTags as $existing) {
            $canonical = trim((string) $existing);
            if ($canonical === '') {
                continue;
            }
            $lowerKey = mb_strtolower($canonical, 'UTF-8');
            $alnumKey = preg_replace('/[^a-z0-9]/u', '', $lowerKey) ?? '';
            $canonicalByLower[$lowerKey] ??= $canonical;
            if ($alnumKey !== '') {
                $canonicalByAlnum[$alnumKey] ??= $canonical;
            }
        }

        $rawTags = preg_split('/[,\r\n;]+/', $tagsString) ?: [];
        $cleanTags = [];
        $seenLower = [];
        $seenAlnum = [];

        foreach ($excludeTags as $excluded) {
            $excludedName = trim((string) $excluded);
            if ($excludedName === '') {
                continue;
            }
            $excludedLower = mb_strtolower($excludedName, 'UTF-8');
            $excludedAlnum = preg_replace('/[^a-z0-9]/u', '', $excludedLower) ?? '';
            $seenLower[$excludedLower] = true;
            if ($excludedAlnum !== '') {
                $seenAlnum[$excludedAlnum] = true;
            }
        }

        foreach ($rawTags as $raw) {
            $tag = preg_replace('/^\s*(?:\d+[\.\)]\s*|[-*•#]+\s*)/u', '', trim($raw)) ?? '';
            $tag = trim($tag, " \t\n\r\0\x0B.\"'");
            $tag = preg_replace('/\s+/', ' ', $tag);
            if ($tag === '' || mb_strlen($tag, 'UTF-8') < 2 || ctype_digit($tag)) {
                continue;
            }

            $rawLower = mb_strtolower($tag, 'UTF-8');
            $rawAlnum = preg_replace('/[^a-z0-9]/u', '', $rawLower) ?? '';

            if (isset($canonicalByLower[$rawLower])) {
                $tag = $canonicalByLower[$rawLower];
            } elseif ($rawAlnum !== '' && isset($canonicalByAlnum[$rawAlnum])) {
                $tag = $canonicalByAlnum[$rawAlnum];
            }

            $normalized = mb_strtolower($tag, 'UTF-8');
            $alnum = preg_replace('/[^a-z0-9]/u', '', $normalized) ?? '';

            if (
                in_array($rawLower, self::GENERIC_TAG_BLOCKLIST, true)
                || in_array($normalized, self::GENERIC_TAG_BLOCKLIST, true)
                || isset($seenLower[$normalized])
                || ($alnum !== '' && isset($seenAlnum[$alnum]))
            ) {
                continue;
            }

            $seenLower[$normalized] = true;
            if ($alnum !== '') {
                $seenAlnum[$alnum] = true;
            }
            $cleanTags[] = $tag;

            if (count($cleanTags) >= $limit) {
                break;
            }
        }

        return $cleanTags;
    }
}