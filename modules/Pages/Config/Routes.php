<?php

use Modules\Pages\Controllers\AdminPages;
use Modules\Pages\Controllers\Pages;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Pages Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Pages\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

  //  example of permissions $routes->get('pages', [AdminPages::class, 'index'], ['filter' => 'admin_filter']);

    $routes->match(['GET', 'POST'], 'pages',  [AdminPages::class, 'index']);
    $routes->post('pages/index', [AdminPages::class, 'index']);

    $routes->match(['GET', 'POST'], 'pages/add',  [AdminPages::class, 'add']);

    $routes->match(['GET', 'POST'], 'pages/edit/(:num)',  [AdminPages::class, 'edit/$1']);

    $routes->post('pages/show/(:num)', [AdminPages::class, 'show/$1']);

    $routes->post('pages/edit', [AdminPages::class, 'edit']);

    $routes->post('pages/switchToggle', [AdminPages::class, 'switchToggle']);

    $routes->post('pages/delete', [AdminPages::class, 'delete']);

});

/*** Route for Pages Site ***/

$routes->group('/', ['namespace' => 'Modules\Pages\Controllers'], static function ($routes) {
    $routes->add('pages/(:any)', [Pages::class, 'view']);
    $routes->get('pages', [Pages::class, 'index']);
    $routes->post('pages/view/(:num)', [Pages::class, 'view/$1']);
    $routes->get('pages/add', [Pages::class, 'add']);
    $routes->get('pages/edit/(:num)', [Pages::class, 'edit/$1']);
    $routes->get('pages/delete/(:num)', [Pages::class, 'delete/$1']);
});

/*** Route for Pages api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('pages');
});*/
