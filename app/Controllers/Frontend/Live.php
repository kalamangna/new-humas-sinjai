<?php

namespace App\Controllers\Frontend;

class Live extends BaseController
{
    public function radio()
    {
        $data = [
            'stream_url'  => base_url('live/radio-proxy')
        ];
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Suara Bersatu FM';
        $data['seo']['description'] = 'Streaming Radio Suara Bersatu FM - Suara Rakyat Sinjai.';
        $data['seo']['keywords'] = 'radio sinjai, suara bersatu fm, streaming radio sinjai';

        return view('frontend/live/radio', $data);
    }

    /**
     * Proxy for radio stream to bypass HTTPS restrictions
     */
    public function radioProxy()
    {
        $url = env('stream.radio.url', 'http://36.95.15.68:8000/sbfm');
        
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 5,
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
            ]
        ]);

        $stream = @fopen($url, 'rb', false, $ctx);
        
        if (!$stream) {
            return $this->response->setStatusCode(404)->setBody('Stream not available');
        }

        // Close session to prevent locking other requests from the same user
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Set headers for audio stream
        header('Content-Type: audio/mpeg');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Connection: close');

        // Pass through the stream
        fpassthru($stream);
        exit;
    }

    public function tv()
    {
        $cacheKey = 'fb_live_video_id';
        $videoId = cache($cacheKey);

        if ($videoId === null) {
            $fbService = new \App\Services\Content\FacebookService();
            $videoId = $fbService->getLiveVideoId();
            
            // Cache result for 60 seconds (store 'none' if offline to avoid frequent API hits)
            $cachedValue = $videoId ?: 'none';
            cache()->save($cacheKey, $cachedValue, 60);
            $videoId = ($cachedValue === 'none') ? null : $cachedValue;
        } else {
            $videoId = ($videoId === 'none') ? null : $videoId;
        }

        $data = [
            'video_id'      => $videoId,
            'is_live'       => !empty($videoId)
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Sinjai TV';
        $data['seo']['description'] = 'Streaming Sinjai TV - Saluran Informasi Pembangunan Daerah.';
        $data['seo']['keywords'] = 'sinjai tv, streaming tv sinjai, live streaming sinjai';

        return view('frontend/live/tv', $data);
    }
}