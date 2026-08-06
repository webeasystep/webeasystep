<?php

use Modules\Bundles\Controllers\AdminBundles;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('admin', [
    'namespace' => 'Modules\Bundles\Controllers',
    'filter'    => 'auth:admin',
], static function ($routes) {
    $routes->get('bundles', [AdminBundles::class, 'index']);
    $routes->match(['GET', 'POST'], 'bundles/add', [AdminBundles::class, 'add']);
    $routes->match(['GET', 'POST'], 'bundles/edit/(:num)', [AdminBundles::class, 'edit/$1']);
    $routes->get('bundles/delete/(:num)', [AdminBundles::class, 'delete/$1']);
});
