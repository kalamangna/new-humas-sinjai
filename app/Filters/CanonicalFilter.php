<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class CanonicalFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri();
        $path = $uri->getPath();
        $redirect = false;

        // 1. Remove trailing slash (except for home page)
        if ($path !== '/' && substr($path, -1) === '/') {
            $uri->setPath(rtrim($path, '/'));
            $redirect = true;
        }

        // 2. Ensure scheme matches baseURL (if baseURL is HTTPS, force HTTPS)
        $baseURL = config('App')->baseURL;
        $baseScheme = parse_url($baseURL, PHP_URL_SCHEME);
        
        if ($baseScheme === 'https' && $uri->getScheme() !== 'https') {
            $uri->setScheme('https');
            $redirect = true;
        }

        if ($redirect) {
            return redirect()->to((string) $uri, 301);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add Canonical Link Header for SEO
        $response->setHeader('Link', '<' . current_url() . '>; rel="canonical"');
    }
}
