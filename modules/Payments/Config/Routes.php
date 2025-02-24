<?php

use Modules\Payments\Controllers\AdminPayments;
use Modules\Payments\Controllers\Payments;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Payments\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'payments', [AdminPayments::class, 'index']);

    $routes->post('payments/index', [AdminPayments::class, 'index']);

    $routes->match(['get', 'post'], 'payments/add', [AdminPayments::class, 'add']);

    $routes->match(['get', 'post'], 'payments/edit/(:num)', [AdminPayments::class, 'edit/$1']);

    $routes->post('payments/show/(:num)', [AdminPayments::class, 'show/$1']);

    $routes->post('payments/edit', [AdminPayments::class, 'edit']);

    $routes->post('payments/switchToggle', [AdminPayments::class, 'switchToggle']);

    $routes->post('payments/delete', [AdminPayments::class, 'delete']);


});

/*** Route for Payments Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Payments\Controllers'],
    static function ($routes) {

    $routes->get('payments', [Payments::class, 'index']);
    $routes->post('payments/show/(:num)', [Payments::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('payments');
});*/
