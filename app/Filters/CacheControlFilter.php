<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CacheControlFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No action needed before
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // 1. Check if it's an admin route
        $uri = $request->getUri()->getPath();
        if (strpos($uri, 'admin') === 0 || strpos($uri, 'login') === 0) {
            // Disable caching for admin and login
            $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->setHeader('Pragma', 'no-cache');
            return;
        }

        // 2. Set default caching for frontend (e.g., 5 minutes)
        if ($response->getStatusCode() === 200) {
            $response->setHeader('Cache-Control', 'public, max-age=300, stale-while-revalidate=60');
        }
    }
}
