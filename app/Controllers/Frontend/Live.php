<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Services\Content\FacebookService;
use Config\Services; // Import Config\Services

class Live extends BaseController
{
    /**
     * @var FacebookService
     */
    protected $facebookService;

    public function __construct() // No arguments here
    {
        // Manually resolve FacebookService via CI4's service container
        $this->facebookService = Services::facebookService(); // Use the service() helper
    }

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
        // Get live stream data from the service
        $liveStreamData = $this->facebookService->getLiveStreamData();
        
        $data = [
            'title'      => 'Sinjai TV',
            'stream_url' => $liveStreamData['embedUrl'], // Use the embedUrl from service
            'is_live'    => $liveStreamData['isLive'],   // Use the isLive from service
            'seo'        => [
                'title'       => 'Sinjai TV',
                'description' => 'Siaran Langsung Sinjai TV - Saluran Informasi Pembangunan Daerah.',
                'keywords'    => 'sinjai tv, streaming tv sinjai, live streaming sinjai'
            ]
        ];
        return view('frontend/live/tv', $data);
    }
}
