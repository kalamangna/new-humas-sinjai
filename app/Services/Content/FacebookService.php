<?php

namespace App\Services\Content;

use CodeIgniter\Config\Services;
use Exception;

class FacebookService
{
    /**
     * Cache key for storing the live video ID
     */
    private const CACHE_KEY = 'facebook_live_video';

    /**
     * Cache duration in seconds
     */
    private const CACHE_TTL = 60;

    protected $pageId;
    protected $pageToken;
    protected $client;
    protected $cache;

    public function __construct()
    {
        $this->pageId    = env('facebook.pageId');
        $this->pageToken = env('facebook.accessToken');
        $this->cache     = Services::cache();
        $this->client    = Services::curlrequest([
            'base_uri' => 'https://graph.facebook.com/v18.0/',
            'timeout'  => 5,
        ]);
    }

    /**
     * Get the current active live video ID from the configured Facebook Page.
     * Implements 60-second caching for both live and offline states.
     * 
     * @return string|null Video ID or null if offline
     */
    public function getLiveVideoId(): ?string
    {
        // 1. Check cache first
        $cached = $this->cache->get(self::CACHE_KEY);
        if ($cached !== null) {
            // Use ':null:' string as a placeholder for cached null results
            return $cached === ':null:' ? null : $cached;
        }

        // 2. Validate configuration
        if (empty($this->pageId) || empty($this->pageToken) || str_contains($this->pageId, 'YOUR_PAGE')) {
            return null;
        }

        $videoId = null;

        try {
            // 3. Fetch from Facebook Graph API
            $response = $this->client->get("{$this->pageId}/live_videos", [
                'query' => [
                    'status'       => 'LIVE_NOW',
                    'access_token' => $this->pageToken,
                    'fields'       => 'id',
                    'limit'        => 1
                ],
                'http_errors' => false // Allow us to handle status codes manually
            ]);

            $statusCode = $response->getStatusCode();
            $body       = json_decode($response->getBody(), true);

            if ($statusCode === 200) {
                if (isset($body['data'][0]['id'])) {
                    $videoId = (string) $body['data'][0]['id'];
                }
            } else {
                $errorMsg = $body['error']['message'] ?? 'Unknown API Error';
                log_message('error', "[FacebookService] API returned {$statusCode}: {$errorMsg}");
            }

        } catch (Exception $e) {
            log_message('error', '[FacebookService] Connection Error: ' . $e->getMessage());
            // Fallback to null (videoId is already null)
        }

        // 4. Cache the result (even if null) for 60 seconds
        // Using ':null:' placeholder because cache->get() returns null on miss
        $this->cache->save(self::CACHE_KEY, $videoId ?? ':null:', self::CACHE_TTL);

        return $videoId;
    }
}