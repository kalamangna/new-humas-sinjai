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
            'stream_url'  => env('stream.radio.url', 'http://103.155.105.10:8000/stream')
        ];
        return view('Frontend/live/radio', $data);
    }

    public function tv()
    {
        $data = [
            'title'       => 'Sinjai TV',
            'description' => 'Streaming Sinjai TV - Saluran Informasi Pembangunan Daerah.',
            'keywords'    => 'sinjai tv, streaming tv sinjai, live streaming sinjai',
            'stream_url'  => env('stream.tv.url', 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fsinjaikab%2Flive&show_text=0&width=560')
        ];
        return view('Frontend/live/tv', $data);
    }
}