<?php

namespace App\Controllers\Frontend;

class Live extends BaseController
{
    public function radio()
    {
        $data = [
            'title'       => 'Suara Bersatu FM',
            'description' => 'Streaming Radio Suara Bersatu FM - Suara Rakyat Sinjai.',
            'keywords'    => 'radio sinjai, suara bersatu fm, streaming radio sinjai'
        ];
        return view('Frontend/live/radio', $data);
    }

    public function tv()
    {
        $data = [
            'title'       => 'Sinjai TV',
            'description' => 'Streaming Sinjai TV - Saluran Informasi Pembangunan Daerah.',
            'keywords'    => 'sinjai tv, streaming tv sinjai, live streaming sinjai'
        ];
        return view('Frontend/live/tv', $data);
    }
}
