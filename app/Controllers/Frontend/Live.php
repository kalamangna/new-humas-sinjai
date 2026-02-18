<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Services\Content\FacebookService;

class Live extends BaseController
{
    public function radio()
    {
        $data = [
            'title'      => 'Suara Bersatu FM',
            'stream_url' => env('stream.radio.url', 'https://streaming.sinjaikab.go.id:8443/sbfm'),
            'seo'        => [
                'title'       => 'Suara Bersatu FM',
                'description' => 'Siaran Langsung Radio Suara Bersatu FM Sinjai - Suara Rakyat Sinjai.',
                'keywords'    => 'radio sinjai, suara bersatu fm, streaming radio sinjai'
            ]
        ];
        return view('frontend/live/radio', $data);
    }

    public function tv()
    {
        $facebookService = new FacebookService();
        $liveVideoId = $facebookService->getLiveVideoId();
        
        $stream_url = $liveVideoId 
            ? "https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fsinjaitv%2Fvideos%2F{$liveVideoId}%2F&show_text=0&width=560"
            : null;

        $data = [
            'title'      => 'Sinjai TV',
            'stream_url' => $stream_url,
            'is_live'    => !empty($stream_url),
            'seo'        => [
                'title'       => 'Sinjai TV',
                'description' => 'Siaran Langsung Sinjai TV - Saluran Informasi Pembangunan Daerah.',
                'keywords'    => 'sinjai tv, streaming tv sinjai, live streaming sinjai'
            ]
        ];
        return view('frontend/live/tv', $data);
    }
}
