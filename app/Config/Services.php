<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\Content\FacebookService; // Import the FacebookService

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    /**
     * The FacebookService for handling Facebook Graph API interactions.
     *
     * @param boolean $getShared
     * @return FacebookService
     */
    public static function facebookService(bool $getShared = true): FacebookService
    {
        if ($getShared) {
            return static::getSharedInstance('facebookService');
        }

        // Resolve its dependencies (CodeIgniter's HTTP Client and Cache)
        $httpClient = static::curlrequest();
        $cache = static::cache();

        return new FacebookService($httpClient, $cache);
    }
}