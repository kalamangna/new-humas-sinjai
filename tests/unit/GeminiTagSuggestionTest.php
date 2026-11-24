<?php

use App\Libraries\GeminiService;
use CodeIgniter\Test\CIUnitTestCase;

final class GeminiTagSuggestionTest extends CIUnitTestCase
{
    private function invokePrivate(GeminiService $service, string $methodName, array $args)
    {
        $method = new ReflectionMethod(GeminiService::class, $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($service, $args);
    }

    public function testBuildPromptIncludesExistingTagsWhenProvided(): void
    {
        $service = new GeminiService('dummy-key');

        $promptWithTags = $this->invokePrivate($service, 'buildPrompt', [
            'Rapat Koordinasi SPBE',
            'Diskominfo Sinjai menggelar rapat koordinasi SPBE.',
            ['Diskominfo Sinjai', 'Pelayanan Publik', '  '],
        ]);

        $this->assertStringContainsString('Daftar Tag Eksisting:', $promptWithTags);
        $this->assertStringContainsString('Diskominfo Sinjai, Pelayanan Publik', $promptWithTags);

        $promptWithoutTags = $this->invokePrivate($service, 'buildPrompt', [
            'Rapat Koordinasi SPBE',
            'Diskominfo Sinjai menggelar rapat koordinasi SPBE.',
            [],
        ]);

        $this->assertStringNotContainsString('Daftar Tag Eksisting:', $promptWithoutTags);
    }

    public function testParseTagsNormalizesVariationsToCanonicalExistingTags(): void
    {
        $service = new GeminiService('dummy-key');
        $existing = ['Pelayanan Publik', 'Idulfitri', 'ATR BPN', 'Diskominfo Sinjai'];

        $parsed = $this->invokePrivate($service, 'parseTags', [
            'pelayanan publik, Idul Fitri, ATR/BPN, Inovasi Daerah',
            $existing,
        ]);

        $this->assertSame([
            'Pelayanan Publik',
            'Idulfitri',
            'ATR BPN',
            'Inovasi Daerah',
        ], $parsed);
    }

    public function testParseTagsDeduplicatesPunctuationVariantsAndFiltersGenericBlocklist(): void
    {
        $service = new GeminiService('dummy-key');

        $parsed = $this->invokePrivate($service, 'parseTags', [
            'Pemerintah, Pemerintahan, Berita Terkini Sinjai, Idul Fitri, Idulfitri, Turnamen Sepak Bola, Turnamen sepakbola, UMKM',
            [],
        ]);

        $this->assertSame([
            'Idul Fitri',
            'Turnamen Sepak Bola',
            'UMKM',
        ], $parsed);
    }
}
