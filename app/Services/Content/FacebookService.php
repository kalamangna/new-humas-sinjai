<?php

namespace App\Services\Content;

use CodeIgniter\Config\Services;
use Exception;

class FacebookService
{
    protected $pageId;
    protected $pageToken;
    protected $client;

    public function __construct()
    {
        $this->pageId = env('facebook.page_id');
        $this->pageToken = env('facebook.page_token');
        $this->client = Services::curlrequest([
            'base_uri' => 'https://graph.facebook.com/v18.0/',
            'timeout'  => 5,
        ]);
    }

    /**
     * Get the current active live video ID from the configured Facebook Page.
     * 
     * @return string|null Video ID or null if offline
     */
    public function getLiveVideoId(): ?string
    {
        if (empty($this->pageId) || empty($this->pageToken) || $this->pageId === 'YOUR_PAGE_ID_HERE') {
            return null;
        }

        try {
            $response = $this->client->get("{$this->pageId}/live_videos", [
                'query' => [
                    'status'       => 'LIVE_NOW',
                    'access_token' => $this->pageToken,
                    'fields'       => 'id',
                    'limit'        => 1
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['data'][0]['id'])) {
                return $result['data'][0]['id'];
            }

            return null;

        } catch (Exception $e) {
            log_message('error', '[FacebookService] API Error: ' . $e->getMessage());
            return null;
        }
    }
}
