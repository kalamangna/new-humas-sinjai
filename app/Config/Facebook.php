<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Facebook extends BaseConfig
{
    /**
     * Facebook Page ID
     * 
     * @var string
     */
    public $pageId = '';

    /**
     * Facebook Page Access Token
     * 
     * @var string
     */
    public $pageAccessToken = '';

    public function __construct()
    {
        parent::__construct();

        $this->pageId = env('facebook.page_id', '');
        $this->pageAccessToken = env('facebook.page_token', '');
    }
}
