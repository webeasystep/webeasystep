<?php


use Modules\Videos\Controllers\AdminVideos;
use Modules\Videos\Controllers\Videos;

if (!isset($routes)) {
    $routes = \Config\Videos::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Videos\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'videos', [AdminVideos::class, 'index']);

    $routes->post('videos/index', [AdminVideos::class, 'index']);

    $routes->match(['GET', 'POST'], 'videos/add', [AdminVideos::class, 'add']);

    $routes->match(['GET', 'POST'], 'videos/edit/(:num)', [AdminVideos::class, 'edit/$1']);

    $routes->post('videos/show/(:num)', [AdminVideos::class, 'show/$1']);

    $routes->post('videos/edit', [AdminVideos::class, 'edit']);

    $routes->post('videos/switchToggle', [AdminVideos::class, 'switchToggle']);

    $routes->match(['GET', 'POST'], 'videos/delete', [AdminVideos::class, 'delete']);
});

/*** Route for Videos Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Videos\Controllers'],
    static function ($routes) {

    $routes->get('videos', [Videos::class, 'index']);
    $routes->get('videos', [Videos::class, 'index']);
    $routes->get('videos/show/(:num)', [Videos::class, 'show_page/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('videos');
});*/
