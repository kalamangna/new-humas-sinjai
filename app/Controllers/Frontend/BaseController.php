<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController as AppBaseController;

class BaseController extends AppBaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Add frontend-specific preloading if necessary
        // The main BaseController already preloads categories and subCategories
    }
}
