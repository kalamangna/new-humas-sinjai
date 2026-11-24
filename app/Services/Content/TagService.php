<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\TagModel;

class TagService extends BaseService
{
    private const PREFERRED_TAGS_CACHE_KEY = 'preferred_existing_tags';
    private const PREFERRED_TAGS_CACHE_TTL = 3600;

    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function getValidationRules(?int $id = null): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => 'permit_empty|alpha_dash|is_unique[tags.slug,id,' . ($id ?? '0') . ']',
        ];
    }

    public function saveTag(array $data, ?int $id = null): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->createAsciiSlug($data['name']);
        }

        if ($id) {
            $result = $this->tagModel->update($id, $data);
        } else {
            $result = $this->tagModel->save($data);
        }

        if ($result) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
        }

        return $result;
    }

    public function deleteTag(int $id): bool
    {
        $result = $this->tagModel->delete($id);
        if ($result) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
        }

        return $result;
    }

    /**
     * Gabungkan tag sumber ke tag tujuan dalam satu transaksi database.
     */
    public function mergeTags(int $sourceId, int $targetId): bool
    {
        if ($sourceId <= 0 || $targetId <= 0 || $sourceId === $targetId) {
            return false;
        }

        $source = $this->tagModel->find($sourceId);
        $target = $this->tagModel->find($targetId);
        if (!$source || !$target) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $db->query(
            'INSERT IGNORE INTO post_tags (post_id, tag_id) SELECT post_id, ? FROM post_tags WHERE tag_id = ?',
            [$targetId, $sourceId]
        );
        $this->tagModel->delete($sourceId);
        $db->transComplete();

        if ($db->transStatus()) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Ambil daftar tag populer eksisting dari cache atau database untuk referensi AI.
     *
     * @return string[]
     */
    public function getPreferredExistingTags(int $limit = 150, int $minPosts = 3): array
    {
        $cached = cache(self::PREFERRED_TAGS_CACHE_KEY);
        if (is_array($cached)) {
            return $cached;
        }

        $tags = $this->tagModel->getPreferredTags($limit, $minPosts);
        cache()->save(self::PREFERRED_TAGS_CACHE_KEY, $tags, self::PREFERRED_TAGS_CACHE_TTL);

        return $tags;
    }

    /**
     * Saring kandidat tag yang berpotensi duplikat di PHP sebelum dievaluasi AI.
     *
     * @return array<int, array{id: int, name: string, post_count: int}>
     */
    public function getMergeCandidateTags(int $maxTags = 120): array
    {
        $rows = $this->tagModel
            ->select('tags.id, tags.name, COUNT(post_tags.post_id) as post_count')
            ->join('post_tags', 'post_tags.tag_id = tags.id', 'left')
            ->groupBy('tags.id')
            ->orderBy('post_count', 'DESC')
            ->orderBy('tags.name', 'ASC')
            ->findAll();

        $allTags = [];
        foreach ($rows as $row) {
            $allTags[(int) $row['id']] = [
                'id'         => (int) $row['id'],
                'name'       => (string) $row['name'],
                'post_count' => (int) ($row['post_count'] ?? 0),
            ];
        }

        if (count($allTags) <= $maxTags) {
            return array_values($allTags);
        }

        $candidateIds = [];

        // 1. Sertakan 35 tag terpopuler sebagai jangkar tag tujuan baku.
        foreach (array_slice(array_keys($allTags), 0, 35) as $topId) {
            $candidateIds[$topId] = true;
        }

        // 2. Kelompokkan berdasarkan base nama (tanpa tanda baca atau embel-embel daerah/dinas).
        $baseGroups = [];
        $tokenGroups = [];
        $stopTokens = ['sinjai', 'daerah', 'berita', 'kabupaten', 'program', 'kegiatan', 'pemerintah', 'sulsel', 'nasional', 'indonesia', 'dinas', 'kantor', 'rapat'];

        foreach ($allTags as $id => $tag) {
            $lower = mb_strtolower(trim($tag['name']), 'UTF-8');
            $base = preg_replace('/\b(pemkab|kabupaten|kab\.?|kota|daerah|dinas)?\s*sinjai\b/u', '', $lower) ?? $lower;
            $base = preg_replace('/[^a-z0-9]/u', '', $base) ?? '';
            if (strlen($base) >= 3) {
                $baseGroups[$base][] = $id;
            }

            $words = preg_split('/[^a-z0-9]+/u', $lower, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            foreach ($words as $word) {
                if (strlen($word) >= 4 && !in_array($word, $stopTokens, true)) {
                    $tokenGroups[$word][] = $id;
                }
            }
        }

        foreach ($baseGroups as $ids) {
            if (count($ids) > 1) {
                foreach ($ids as $id) {
                    $candidateIds[$id] = true;
                }
            }
        }

        foreach ($tokenGroups as $ids) {
            if (count($ids) >= 2 && count($ids) <= 6) {
                foreach ($ids as $id) {
                    $candidateIds[$id] = true;
                }
            }
        }

        $candidates = [];
        foreach (array_keys($candidateIds) as $id) {
            if (isset($allTags[$id])) {
                $candidates[] = $allTags[$id];
            }
            if (count($candidates) >= $maxTags) {
                break;
            }
        }

        return $candidates;
    }

    /**
     * Dapatkan rekomendasi penggabungan tag dari Gemini AI lengkap dengan metadata tag.
     *
     * @return array<int, array<string, mixed>>
     */
    public function detectDuplicateSuggestions(): array
    {
        $candidates = $this->getMergeCandidateTags(120);
        if (count($candidates) < 2) {
            return [];
        }

        $byId = [];
        foreach ($candidates as $tag) {
            $byId[$tag['id']] = $tag;
        }

        $geminiService = new \App\Libraries\GeminiService();
        $rawSuggestions = $geminiService->suggestTagMerges($candidates);

        $enriched = [];
        foreach ($rawSuggestions as $item) {
            $srcId = (int) $item['source_id'];
            $dstId = (int) $item['target_id'];
            if (!isset($byId[$srcId], $byId[$dstId])) {
                continue;
            }

            // Pastikan tag dengan jumlah berita lebih banyak selalu menjadi target.
            if ($byId[$srcId]['post_count'] > $byId[$dstId]['post_count']) {
                [$srcId, $dstId] = [$dstId, $srcId];
            }

            $enriched[] = [
                'source_id'    => $srcId,
                'source_name'  => $byId[$srcId]['name'],
                'source_count' => $byId[$srcId]['post_count'],
                'target_id'    => $dstId,
                'target_name'  => $byId[$dstId]['name'],
                'target_count' => $byId[$dstId]['post_count'],
                'reason'       => $item['reason'],
            ];
        }

        return $enriched;
    }

    /**
     * Eksekusi penggabungan banyak pasangan tag sekaligus secara aman.
     *
     * @param string[] $pairs Format "source_id:target_id"
     * @return array{merged: array<int, array{source_name: string, target_name: string}>, failed: int}
     */
    public function mergeBatch(array $pairs): array
    {
        $merged = [];
        $failed = 0;
        $usedSources = [];
        $usedTargets = [];

        foreach ($pairs as $pair) {
            if (!is_string($pair) || !str_contains($pair, ':')) {
                continue;
            }
            [$srcStr, $dstStr] = explode(':', $pair, 2);
            $sourceId = (int) $srcStr;
            $targetId = (int) $dstStr;

            if (
                $sourceId <= 0
                || $targetId <= 0
                || $sourceId === $targetId
                || isset($usedSources[$sourceId], $usedTargets[$sourceId], $usedSources[$targetId])
            ) {
                $failed++;
                continue;
            }

            $source = $this->tagModel->find($sourceId);
            $target = $this->tagModel->find($targetId);
            if (!$source || !$target) {
                $failed++;
                continue;
            }

            if ($this->mergeTags($sourceId, $targetId)) {
                $usedSources[$sourceId] = true;
                $usedTargets[$targetId] = true;
                $merged[] = [
                    'source_name' => (string) $source['name'],
                    'target_name' => (string) $target['name'],
                ];
            } else {
                $failed++;
            }
        }

        return [
            'merged' => $merged,
            'failed' => $failed,
        ];
    }

    public function suggestTags(string $title, string $content): array
    {
        $existingTags = $this->getPreferredExistingTags();
        $geminiService = new \App\Libraries\GeminiService();
        return $geminiService->suggestTags($title, $content, $existingTags);
    }
}