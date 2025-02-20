<?php

use Modules\Sections\Controllers\AdminSections;
use Modules\Sections\Controllers\Sections;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Sections\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

  //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'sections',  [AdminSections::class, 'index']);

    $routes->post('sections/index', [AdminSections::class, 'index']);

    $routes->match(['get', 'post'], 'sections/add',  [AdminSections::class, 'add']);

    $routes->match(['get', 'post'], 'sections/edit/(:num)',  [AdminSections::class, 'edit/$1']);

    $routes->post('sections/show/(:num)', [AdminSections::class, 'show/$1']);

    $routes->post('sections/edit', [AdminSections::class, 'edit']);

    $routes->post('sections/switchToggle', [AdminSections::class, 'switchToggle']);

    $routes->post('sections/delete', [AdminSections::class, 'delete']);



});

/*** Route for Sections Site ***/

$routes->group('/', ['namespace' => 'Modules\Sections\Controllers'], static function ($routes) {

    $routes->get('sections', [Sections::class, 'index']);
    $routes->post('sections/show/(:num)', [Sections::class, 'show/$1']);
    $routes->get('sections/add', [Sections::class, 'add']);
    $routes->get('sections/edit/(:num)', [Sections::class, 'edit/$1']);
    $routes->get('sections/delete/(:num)', [Sections::class, 'delete/$1']);
});

/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('sections');
});*/
