<?php

use Modules\Plans\Controllers\AdminSubscriptions;
use Modules\Plans\Controllers\Subscriptions;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Plans\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'plans', [AdminSubscriptions::class, 'index']);

    $routes->post('plans/index', [AdminSubscriptions::class, 'index']);

    $routes->match(['get', 'post'], 'plans/add', [AdminSubscriptions::class, 'add']);

    $routes->match(['get', 'post'], 'plans/edit/(:num)', [AdminSubscriptions::class, 'edit/$1']);

    $routes->post('plans/show/(:num)', [AdminSubscriptions::class, 'show/$1']);

    $routes->post('plans/edit', [AdminSubscriptions::class, 'edit']);

    $routes->post('plans/switchToggle', [AdminSubscriptions::class, 'switchToggle']);

    $routes->get('plans/delete', [AdminSubscriptions::class, 'delete']);


});

/*** Route for Plans Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Plans\Controllers'],
    static function ($routes) {

    $routes->get('plans', [Subscriptions::class, 'index']);
    $routes->post('plans/show/(:num)', [Subscriptions::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('plans');
});*/
