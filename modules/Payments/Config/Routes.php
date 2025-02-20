<?php

use Modules\Payments\Controllers\AdminPlans;
use Modules\Payments\Controllers\Plans;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Payments\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'payments', [AdminPlans::class, 'index']);

    $routes->post('payments/index', [AdminPlans::class, 'index']);

    $routes->match(['get', 'post'], 'payments/add', [AdminPlans::class, 'add']);

    $routes->match(['get', 'post'], 'payments/edit/(:num)', [AdminPlans::class, 'edit/$1']);

    $routes->post('payments/show/(:num)', [AdminPlans::class, 'show/$1']);

    $routes->post('payments/edit', [AdminPlans::class, 'edit']);

    $routes->post('payments/switchToggle', [AdminPlans::class, 'switchToggle']);

    $routes->get('payments/delete', [AdminPlans::class, 'delete']);


});

/*** Route for Payments Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Payments\Controllers'],
    static function ($routes) {

    $routes->get('payments', [Plans::class, 'index']);
    $routes->post('payments/show/(:num)', [Plans::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('payments');
});*/
