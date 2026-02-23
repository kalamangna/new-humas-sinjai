<?php

namespace App\Services\Content;

use CodeIgniter\Cache\Handlers\FileHandler; // Changed from CodeIgniter\Cache\Cache
use CodeIgniter\HTTP\CURLRequest;
use Config\Facebook;
use Exception;

class FacebookService
{
    /**
     * @var Facebook
     */
    protected $config;

    /**
     * @var CURLRequest
     */
    protected $httpClient;

    /**
     * @var FileHandler // Changed type hint here
     */
    protected $cache;

    public function __construct(CURLRequest $httpClient, FileHandler $cache) // Changed type hint here
    {
        $this->config = config('Facebook');
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    /**
     * Get Facebook Live Stream Data
     * 
     * @return array Returns ['isLive' => bool, 'embedUrl' => string|null]
     */
    public function getLiveStreamData(): array
    {
        $cacheKey = 'facebook_live_status';
        $cachedData = $this->cache->get($cacheKey);

        if ($cachedData !== null) {
            return $cachedData;
        }

        $pageId = $this->config->pageId;
        $accessToken = $this->config->pageAccessToken;

        if (empty($pageId) || empty($accessToken)) {
            log_message('error', 'Facebook API credentials (page_id or page_token) are missing in .env.');
            return ['isLive' => false, 'embedUrl' => null];
        }

        $apiUrl = "https://graph.facebook.com/v19.0/{$pageId}/live_videos";
        $queryParams = [
            'fields'      => 'id,embed_html,status',
            'status'      => 'LIVE_NOW',
            'access_token' => $accessToken,
        ];

        try {
            $response = $this->httpClient->get($apiUrl, $queryParams);

            if ($response->getStatusCode() !== 200) {
                log_message('error', 'Facebook Graph API Error: HTTP Status ' . $response->getStatusCode() . ' - ' . $response->getBody());
                return ['isLive' => false, 'embedUrl' => null];
            }

            $result = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                log_message('error', 'Facebook Graph API Error: Invalid JSON response - ' . json_last_error_msg());
                return ['isLive' => false, 'embedUrl' => null];
            }

            if (isset($result['error'])) {
                log_message('error', 'Facebook Graph API Error: ' . $result['error']['message']);
                return ['isLive' => false, 'embedUrl' => null];
            }

            if (!empty($result['data'])) {
                // Find the first live video
                foreach ($result['data'] as $video) {
                    if (isset($video['status']) && $video['status'] === 'LIVE_NOW') {
                        $videoId = $video['id'];
                        $embedUrl = "https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/watch/?v={$videoId}&show_text=false";
                        $data = ['isLive' => true, 'embedUrl' => $embedUrl];
                        $this->cache->save($cacheKey, $data, 60); // Cache for 60 seconds
                        return $data;
                    }
                }
            }

            // No live video found
            $data = ['isLive' => false, 'embedUrl' => null];
            $this->cache->save($cacheKey, $data, 60); // Cache the 'not live' status too
            return $data;

        } catch (Exception $e) {
            log_message('error', 'Facebook Graph API Exception: ' . $e->getMessage());
            return ['isLive' => false, 'embedUrl' => null];
        }
    }
}
