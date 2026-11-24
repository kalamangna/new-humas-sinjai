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

    public function testBuildTagMergePromptIncludesCandidateTagsAndStrictRules(): void
    {
        $service = new GeminiService('dummy-key');

        $prompt = $this->invokePrivate($service, 'buildTagMergePrompt', [
            "10: Diskominfo Sinjai (35 berita)\n11: Kominfo Sinjai (5 berita)",
            15,
        ]);

        $this->assertStringContainsString('maksimal 15 pasangan tag', $prompt);
        $this->assertStringContainsString('10: Diskominfo Sinjai (35 berita)', $prompt);
        $this->assertStringContainsString('Bappeda Sinjai != Bapenda Sinjai', $prompt);
    }

    public function testParseTagMergeSuggestionsFiltersInvalidAndChainedMerges(): void
    {
        $service = new GeminiService('dummy-key');
        $validIds = [10 => true, 11 => true, 20 => true, 21 => true];

        $raw = "```json\n" . json_encode([
            ['source_id' => 11, 'target_id' => 10, 'reason' => 'Singkatan OPD sama'],
            ['source_id' => 10, 'target_id' => 20, 'reason' => 'Rantai merge harus ditolak'],
            ['source_id' => 20, 'target_id' => 20, 'reason' => 'Self merge ditolak'],
            ['source_id' => 999, 'target_id' => 21, 'reason' => 'ID asing ditolak'],
            ['source_id' => 21, 'target_id' => 20, 'reason' => 'Variasi ejaan'],
        ]) . "\n```";

        $parsed = $this->invokePrivate($service, 'parseTagMergeSuggestions', [
            $raw,
            $validIds,
            25,
        ]);

        $this->assertSame([
            ['source_id' => 11, 'target_id' => 10, 'reason' => 'Singkatan OPD sama'],
            ['source_id' => 21, 'target_id' => 20, 'reason' => 'Variasi ejaan'],
        ], $parsed);
    }
}
