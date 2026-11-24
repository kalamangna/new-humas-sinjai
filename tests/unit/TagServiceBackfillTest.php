<?php

use App\Services\Content\TagService;
use CodeIgniter\Test\CIUnitTestCase;

final class TagServiceBackfillTest extends CIUnitTestCase
{
    private function invokeRankBackfillTags(TagService $service, array $args): array
    {
        $method = new ReflectionMethod(TagService::class, 'rankBackfillTags');
        $method->setAccessible(true);

        return $method->invokeArgs($service, $args);
    }

    public function testRankBackfillTagsPrioritizesRelevantMatchAndAvoidsSubstrings(): void
    {
        $service = new TagService();

        $candidates = [
            ['id' => 1, 'name' => 'Makassar', 'lower' => 'makassar', 'post_count' => 80],
            ['id' => 2, 'name' => 'Porprov', 'lower' => 'porprov', 'post_count' => 60],
            ['id' => 3, 'name' => 'Atlet Sinjai', 'lower' => 'atlet sinjai', 'post_count' => 20],
            ['id' => 4, 'name' => 'Olahraga Sulsel', 'lower' => 'olahraga sulsel', 'post_count' => 15],
            ['id' => 5, 'name' => 'Porprov Sulsel 2026', 'lower' => 'porprov sulsel 2026', 'post_count' => 25],
        ];

        // Sudah ada 'Porprov Sulsel 2026' (id=5), sehingga 'Porprov' (id=2) adalah substring dan harus dihindari dulu.
        $picked = $this->invokeRankBackfillTags($service, [
            'Atlet Sinjai Raih Medali Emas di Porprov',
            'Kontingen Sinjai tampil gemilang di cabang olahraga Sulsel.',
            'Olahraga',
            [5],
            ['Porprov Sulsel 2026'],
            $candidates,
            2,
        ]);

        $this->assertCount(2, $picked);
        $this->assertSame([3, 4], $picked);
    }

    public function testRankBackfillTagsFallsBackToPopularCandidateWhenNoTextMatch(): void
    {
        $service = new TagService();

        $candidates = [
            ['id' => 10, 'name' => 'Bupati Sinjai', 'lower' => 'bupati sinjai', 'post_count' => 500],
            ['id' => 11, 'name' => 'Sulawesi Selatan', 'lower' => 'sulawesi selatan', 'post_count' => 150],
            ['id' => 12, 'name' => 'Kesehatan', 'lower' => 'kesehatan', 'post_count' => 30],
        ];

        $picked = $this->invokeRankBackfillTags($service, [
            'Agenda Rapat Internal',
            'Pembahasan jadwal kegiatan rutin.',
            'Pemerintahan',
            [10],
            ['Bupati Sinjai'],
            $candidates,
            1,
        ]);

        $this->assertSame([11], $picked);
    }
}
