<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController as AppBaseController;

class BaseController extends AppBaseController
{
    protected $seoData = [];

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        helper(['seo']);
        
        // Default SEO data
        $this->seoData = [
            'title' => '',
            'description' => 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki',
            'keywords' => 'Humas Sinjai, Berita Sinjai, Sinjai, Pemerintah Kabupaten Sinjai',
            'image' => base_url('meta.png'),
            'type' => 'website'
        ];
    }
}
