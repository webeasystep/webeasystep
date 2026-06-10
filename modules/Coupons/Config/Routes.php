<?php

use Modules\Coupons\Controllers\AdminCoupons;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Coupons Admin ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Coupons\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    $routes->match(['GET', 'POST'], 'coupons', [AdminCoupons::class, 'index']);
    $routes->post('coupons/index', [AdminCoupons::class, 'index']);
    $routes->match(['GET', 'POST'], 'coupons/add', [AdminCoupons::class, 'add']);
    $routes->match(['GET', 'POST'], 'coupons/edit/(:num)', [AdminCoupons::class, 'edit/$1']);
    $routes->post('coupons/show/(:num)', [AdminCoupons::class, 'show/$1']);
    $routes->post('coupons/switchToggle', [AdminCoupons::class, 'switchToggle']);
    $routes->post('coupons/delete', [AdminCoupons::class, 'delete']);
});
