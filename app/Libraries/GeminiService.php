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
        'gemini-3.6-flash',       // Terbaru & tercepat
        'gemini-3.5-flash',       // Stabil terbaru
        'gemini-2.5-flash',       // Proven stable
        'gemini-2.5-flash-lite',  // Fallback ringan
        'gemini-2.0-flash',       // Generasi sebelumnya
    ];

    private const HARDCODED_FALLBACK = [
        'Sinjai', 'Berita Sinjai', 'Kabupaten Sinjai', 'Pemerintah Daerah',
        'Humas Sinjai', 'Informasi Publik', 'Sulawesi Selatan', 'Diskominfo Sinjai',
        'Bupati Sinjai', 'Berita Daerah',
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

        foreach (self::MODEL_CHAIN as $index => $model) {
            try {
                $tags = $this->attemptSuggestion($title, $content, $model);
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

    private function attemptSuggestion(string $title, string $content, string $model): array
    {
        $response = $this->makeApiRequest($title, $content, $model);
        $tagsString = $this->extractTagsFromResponse($response);
        return $this->parseTags($tagsString);
    }

    private function validateApiKey(): bool
    {
        if (empty($this->apiKey)) {
            log_message('error', '[GeminiService] GEMINI_API_KEY is not set.');
            return false;
        }
        return true;
    }

    private function makeApiRequest(string $title, string $content, string $model): array
    {
        $url = $this->buildApiUrl($model);
        $payload = $this->buildRequestPayload($title, $content);

        // Exceptions bubble up ke suggestTags untuk trigger next model dalam chain.
        $response = $this->httpClient->post($url, [
            'json'        => $payload,
            'timeout'     => 30,
            'http_errors' => true,
        ]);

        return json_decode($response->getBody(), true) ?? [];
    }

    private function buildApiUrl(string $model): string
    {
        return self::BASE_URL . $model . ':generateContent?key=' . $this->apiKey;
    }

    private function buildRequestPayload(string $title, string $content): array
    {
        $cleanedContent = $this->prepareContent($content);
        $prompt = $this->buildPrompt($title, $cleanedContent);

        return [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];
    }

    private function prepareContent(string $content): string
    {
        return substr(strip_tags($content), 0, self::MAX_CONTENT_LENGTH);
    }

    private function buildPrompt(string $title, string $content): string
    {
        return sprintf(
            "Berdasarkan judul dan konten berita di bawah ini, hasilkan 10 tag SEO singkat dan relevan.

            Aturan:
            - Tampilkan hanya daftar tag, pisahkan dengan koma.
            - Jangan berikan penjelasan, pengantar, atau kalimat tambahan.
            - Jangan beri nomor atau bullet.
            - Gunakan kata atau frasa pendek yang umum dipakai di berita Indonesia.
            - Mulai langsung dari daftar tag, tanpa teks lain.

            Judul: \"%s\"
            Konten: \"%s\"",
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
        $tags = array_filter(array_map('trim', explode(',', $tagsString)));
        return array_values($tags);
    }
}