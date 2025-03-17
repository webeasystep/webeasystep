<?php

use Modules\Payments\Controllers\AdminPayments;
use Modules\Payments\Controllers\Payments;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Payments ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Payments\Controllers',
    'filter'    => 'admin_filter'
], static function ($routes) {
    // Example admin routes for managing payments
    $routes->match(['GET', 'POST'], 'payments', [AdminPayments::class, 'index']);
    $routes->post('payments/index', [AdminPayments::class, 'index']);
    $routes->match(['GET', 'POST'], 'payments/add', [AdminPayments::class, 'add']);
    $routes->match(['GET', 'POST'], 'payments/edit/(:num)', [AdminPayments::class, 'edit/$1']);
    $routes->post('payments/show/(:num)', [AdminPayments::class, 'show/$1']);
    $routes->post('payments/edit', [AdminPayments::class, 'edit']);
    $routes->post('payments/switchToggle', [AdminPayments::class, 'switchToggle']);
    $routes->post('payments/delete', [AdminPayments::class, 'delete']);
});

/*** Site Routes for Payments ***/
$routes->group('/', [
    'namespace' => 'Modules\Payments\Controllers'
], static function ($routes) {
    // 1) Show list or index
    $routes->get('payments', [Payments::class, 'index']);
    // 2) Possibly a "show" route if needed
    $routes->post('show/(:num)', [Payments::class, 'show/$1']);
    // 3) New route for purchasing a course (GET/POST)
    //    This calls purchase($courseId) in your Payments controller
    $routes->match(['GET', 'POST'], 'checkout/(:num)', [Payments::class, 'checkout/$1']);
});

/*** Example API Routes (commented out) ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('payments');
});
*/
