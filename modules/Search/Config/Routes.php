<?php

use Modules\Search\Controllers\AdminSearch;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Search\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'search/orders_drivers_interactions',  [AdminSearch::class, 'orders_drivers_interactions']);
    $routes->match(['GET', 'POST'], 'search/drivers_unpaid_orders',  [AdminSearch::class, 'drivers_unpaid_orders']);

    $routes->match(['GET', 'POST'], 'search/add',  [AdminSearch::class, 'add']);

    $routes->match(['GET', 'POST'], 'search/edit/(:num)',  [AdminSearch::class, 'edit/$1']);

    $routes->post('search/show/(:num)', [AdminSearch::class, 'show/$1']);

    $routes->post('search/edit', [AdminSearch::class, 'edit']);

    $routes->post('search/switchToggle', [AdminSearch::class, 'switchToggle']);

    $routes->get('search/delete', [AdminSearch::class, 'delete']);

    $routes->post('search/delete', [AdminSearch::class, 'delete']);


});

/*** Route for Sections Site ***/

$routes->group('/', ['namespace' => 'Modules\search\Controllers'], static function ($routes) {

    $routes->get('search', [Search::class, 'index']);

    $routes->match(['GET', 'POST'], 'search',  [Search::class, 'index']);
});

/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('search');
});*/
