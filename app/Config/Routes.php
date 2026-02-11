<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Frontend Routes
$routes->get('/', 'Frontend\Home::index');
$routes->get('posts', 'Frontend\Home::posts');
$routes->get('post/(:segment)', 'Frontend\Home::post/$1');
$routes->get('category/(:segment)', 'Frontend\Home::category/$1');
$routes->get('tag/(:segment)', 'Frontend\Home::tag/$1');
$routes->get('tags', 'Frontend\Home::tags');
$routes->get('about', 'Frontend\Page::about');
$routes->get('contact', 'Frontend\Page::contact');
$routes->get('widget', 'Frontend\Page::widget');
$routes->get('search', 'Frontend\Home::search');
$routes->get('profil/(:segment)', 'Frontend\Page::profile/$1');
$routes->get('categories', 'Frontend\Home::categories');
$routes->get('rss', 'Frontend\Home::rss');
$routes->get('sitemap.xml', 'Frontend\Home::sitemap');
$routes->get('program-prioritas', 'Frontend\Home::programPrioritas');

// Auth Routes
$routes->get('login', 'Auth\Login::index');
$routes->post('login', 'Auth\Login::attemptLogin');
$routes->get('logout', 'Auth\Login::logout');
$routes->get('auth/login', 'Auth\Login::index');
$routes->post('auth/login', 'Auth\Login::login');

// API Routes
$routes->group('api', static function ($routes) {
    $routes->post('tags/suggest', 'Api\TagSuggestion::suggest');
    
    // Analytics API
    $routes->group('analytics', static function ($routes) {
        $routes->get('overview', 'Admin\Analytics::overview');
        $routes->get('top-pages', 'Admin\Analytics::topPages');
        $routes->get('traffic-sources', 'Admin\Analytics::trafficSources');
        $routes->get('geo', 'Admin\Analytics::geo');
        $routes->get('device-category', 'Admin\Analytics::deviceCategory');
        $routes->get('popular-posts', 'Admin\Analytics::popularPosts');
        $routes->get('monthly-post-stats', 'Admin\Analytics::monthlyPostStats');
        $routes->get('monthly-user-stats', 'Admin\Analytics::monthlyUserStats');
    });
});

// Admin Dashboard Routes
$routes->group('admin', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('profile', 'Admin\Users::profile');
    $routes->get('settings', 'Admin\Users::settings');
    $routes->post('users/update_settings', 'Admin\Users::update_settings');
    
    // Admin Analytics
    $routes->group('analytics', static function ($routes) {
        $routes->get('overview', 'Admin\Analytics::overviewView');
        $routes->get('top-pages', 'Admin\Analytics::topPagesView');
        $routes->get('traffic-sources', 'Admin\Analytics::trafficSourcesView');
        $routes->get('geo', 'Admin\Analytics::geoView');
        $routes->get('device-category', 'Admin\Analytics::deviceCategoryView');
        $routes->get('monthly-report/(:num)/(:num)', 'Admin\Analytics::monthlyReport/$1/$2');
        $routes->get('monthly-report', 'Admin\Analytics::monthlyReport');
        $routes->get('download-monthly-report/(:num)/(:num)', 'Admin\Analytics::downloadMonthlyReportPdf/$1/$2');
    });

    // Resources
    $routes->resource('posts', ['controller' => 'Admin\Posts']);
    $routes->post('posts/upload_image', 'Admin\Posts::upload_image');
    $routes->resource('categories', ['controller' => 'Admin\Categories']);
    $routes->resource('tags', ['controller' => 'Admin\Tags']);
    $routes->resource('profiles', ['controller' => 'Admin\Profiles']);
    $routes->resource('carousel', ['controller' => 'Admin\Carousel', 'except' => 'show']);
    $routes->resource('users', ['controller' => 'Admin\Users', 'placeholder' => '(:num)', 'filter' => 'admin']);
});

// Custom 404 page for the frontend
$routes->set404Override('App\Controllers\Frontend\Home::error404');
