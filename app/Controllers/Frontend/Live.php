<?php

namespace App\Controllers\Frontend;

class Live extends BaseController
{
    public function radio()
    {
        $data = [
            'title'       => 'Suara Bersatu FM',
            'description' => 'Streaming Radio Suara Bersatu FM - Suara Rakyat Sinjai.',
            'keywords'    => 'radio sinjai, suara bersatu fm, streaming radio sinjai',
            'stream_url'  => base_url('live/radio-proxy')
        ];
        return view('Frontend/live/radio', $data);
    }

    /**
     * Proxy for radio stream to bypass HTTPS restrictions
     */
    public function radioProxy()
    {
        $url = env('stream.radio.url', 'http://36.95.15.68:8000/sbfm');
        
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n"
            ]
        ]);

        $stream = @fopen($url, 'rb', false, $ctx);
        
        if (!$stream) {
            return $this->response->setStatusCode(404)->setBody('Stream not available');
        }

        // Set headers for audio stream
        header('Content-Type: audio/mpeg');
        header('Cache-Control: no-cache');
        header('Pragma: no-cache');
        header('Connection: close');

        // Pass through the stream
        fpassthru($stream);
        exit;
    }

    public function tv()
    {
        // Get URL from env or hardcoded fallback
        $url = env('stream.tv.url', 'https://www.facebook.com/watch/live/?v=123456789'); // Placeholder or last known good URL
        
        // Convert mobile links to desktop links
        $url = str_replace(['m.facebook.com', 'mobile.facebook.com', 'fb.watch'], 'www.facebook.com', $url);
        
        // If it's a watch or live URL, ensure we only keep the video ID parameter
        if (strpos($url, 'facebook.com/watch') !== false || strpos($url, 'facebook.com/video.php') !== false) {
            if (preg_match('/v=(\d+)/', $url, $matches)) {
                $url = 'https://www.facebook.com/video.php?v=' . $matches[1];
            }
        }

        $activeStream = [
            'title' => 'Sinjai TV',
            'live_url' => $url
        ];

        $data = [
            'title'         => 'Sinjai TV',
            'description'   => 'Streaming Sinjai TV - Saluran Informasi Pembangunan Daerah.',
            'keywords'      => 'sinjai tv, streaming tv sinjai, live streaming sinjai',
            'active_stream' => $activeStream,
            'stream_url'    => $url
        ];
        return view('Frontend/live/tv', $data);
    }
}