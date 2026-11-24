<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\TagModel;

class TagService extends BaseService
{
    private const PREFERRED_TAGS_CACHE_KEY = 'preferred_existing_tags';
    private const PREFERRED_TAGS_CACHE_TTL = 3600;
    private const TARGET_TAGS_PER_POST = 5;
    private const BACKFILL_GENERIC_BLOCKLIST = [
        'pemerintah',
        'pemerintahan',
        'masyarakat sinjai',
        'berita terkini sinjai',
        'berita terkini',
        'berita sinjai',
        'humas sinjai',
        'pemkab sinjai',
        'kabupaten sinjai',
        'sinjai',
    ];

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
        $overlapRows = $db->query(
            'SELECT a.post_id FROM post_tags a INNER JOIN post_tags b ON a.post_id = b.post_id WHERE a.tag_id = ? AND b.tag_id = ?',
            [$sourceId, $targetId]
        )->getResultArray();
        $overlapPostIds = array_values(array_unique(array_map('intval', array_column($overlapRows, 'post_id'))));

        $db->query(
            'INSERT IGNORE INTO post_tags (post_id, tag_id) SELECT post_id, ? FROM post_tags WHERE tag_id = ?',
            [$targetId, $sourceId]
        );
        $this->tagModel->delete($sourceId);

        if (!empty($overlapPostIds)) {
            $this->backfillPostsToTargetTagCount($db, $overlapPostIds, self::TARGET_TAGS_PER_POST);
        }

        $db->transComplete();

        if ($db->transStatus()) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Lengkapi berita yang jumlah tag-nya turun di bawah target akibat penggabungan tag kembar.
     *
     * @param \CodeIgniter\Database\BaseConnection $db
     * @param int[]                                $postIds
     */
    private function backfillPostsToTargetTagCount($db, array $postIds, int $targetCount): void
    {
        $tagRows = $db->query(
            'SELECT t.id, t.name, COUNT(pt.post_id) AS post_count
             FROM tags t
             INNER JOIN post_tags pt ON pt.tag_id = t.id
             GROUP BY t.id
             HAVING post_count >= 2 AND CHAR_LENGTH(t.name) >= 3
             ORDER BY post_count DESC'
        )->getResultArray();

        $candidateTags = [];
        foreach ($tagRows as $row) {
            $lower = mb_strtolower(trim((string) $row['name']), 'UTF-8');
            if (in_array($lower, self::BACKFILL_GENERIC_BLOCKLIST, true)) {
                continue;
            }
            $candidateTags[] = [
                'id'         => (int) $row['id'],
                'name'       => (string) $row['name'],
                'lower'      => $lower,
                'post_count' => (int) $row['post_count'],
            ];
        }

        if (empty($candidateTags)) {
            return;
        }

        foreach ($postIds as $postId) {
            $currentRows = $db->query(
                'SELECT t.id, t.name FROM post_tags pt INNER JOIN tags t ON t.id = pt.tag_id WHERE pt.post_id = ?',
                [$postId]
            )->getResultArray();

            $need = $targetCount - count($currentRows);
            if ($need <= 0) {
                continue;
            }

            $post = $db->query('SELECT title, content FROM posts WHERE id = ?', [$postId])->getRowArray();
            if (!$post) {
                continue;
            }

            $catRows = $db->query(
                'SELECT c.name FROM post_categories pc INNER JOIN categories c ON c.id = pc.category_id WHERE pc.post_id = ?',
                [$postId]
            )->getResultArray();
            $categoryNames = implode(' ', array_column($catRows, 'name'));

            $pickedIds = $this->rankBackfillTags(
                (string) ($post['title'] ?? ''),
                (string) ($post['content'] ?? ''),
                $categoryNames,
                array_map('intval', array_column($currentRows, 'id')),
                array_column($currentRows, 'name'),
                $candidateTags,
                $need
            );

            foreach ($pickedIds as $tagId) {
                $db->query('INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)', [$postId, $tagId]);
            }
        }
    }

    /**
     * Pilih tag eksisting paling relevan untuk melengkapi kekurangan tag pada berita.
     *
     * @param int[]                                                               $currentTagIds
     * @param string[]                                                            $currentTagNames
     * @param array<int, array{id: int, name: string, lower: string, post_count: int}> $candidateTags
     * @return int[]
     */
    private function rankBackfillTags(
        string $title,
        string $content,
        string $categoryNames,
        array $currentTagIds,
        array $currentTagNames,
        array $candidateTags,
        int $need
    ): array {
        if ($need <= 0) {
            return [];
        }

        $titleLower = mb_strtolower($title, 'UTF-8');
        $contentLower = mb_strtolower(strip_tags($content), 'UTF-8');
        $categoryLower = mb_strtolower($categoryNames, 'UTF-8');
        $currentLowers = array_map(
            static fn ($name) => mb_strtolower(trim((string) $name), 'UTF-8'),
            $currentTagNames
        );

        $primaryScores = [];
        $fallbackScores = [];

        foreach ($candidateTags as $tag) {
            $tagId = (int) $tag['id'];
            if (in_array($tagId, $currentTagIds, true)) {
                continue;
            }

            $tagLower = $tag['lower'];
            $pattern = '/(?<![\p{L}\p{N}])' . preg_quote($tagLower, '/') . '(?![\p{L}\p{N}])/u';
            $score = 0.0;

            if (preg_match($pattern, $titleLower)) {
                $score += 30.0 + min(10.0, strlen($tagLower) * 0.5);
            }
            if (preg_match($pattern, $contentLower)) {
                $score += 12.0 + min(8.0, strlen($tagLower) * 0.3);
            }
            if ($categoryLower !== '' && preg_match($pattern, $categoryLower)) {
                $score += 10.0;
            }

            if ($score <= 0.0) {
                continue;
            }

            $totalScore = $score + min(2.0, log($tag['post_count'] + 1) * 0.3);
            $isSubOrSuper = false;
            foreach ($currentLowers as $curLower) {
                if ($curLower === $tagLower || str_contains($curLower, $tagLower) || str_contains($tagLower, $curLower)) {
                    $isSubOrSuper = true;
                    break;
                }
            }

            if ($isSubOrSuper) {
                $fallbackScores[$tagId] = $totalScore;
            } else {
                $primaryScores[$tagId] = $totalScore;
            }
        }

        arsort($primaryScores);
        $picked = array_slice(array_keys($primaryScores), 0, $need);

        if (count($picked) < $need) {
            arsort($fallbackScores);
            $picked = array_merge(
                $picked,
                array_slice(array_keys($fallbackScores), 0, $need - count($picked))
            );
        }

        if (count($picked) < $need) {
            foreach ($candidateTags as $tag) {
                $tagId = (int) $tag['id'];
                if (!in_array($tagId, $currentTagIds, true) && !in_array($tagId, $picked, true)) {
                    $picked[] = $tagId;
                    if (count($picked) >= $need) {
                        break;
                    }
                }
            }
        }

        return $picked;
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