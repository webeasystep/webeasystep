<?php

use Modules\Plans\Controllers\AdminPlans;
use Modules\Plans\Controllers\Plans;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Plans\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'plans', [AdminPlans::class, 'index']);

    $routes->post('plans/index', [AdminPlans::class, 'index']);

    $routes->match(['get', 'post'], 'plans/add', [AdminPlans::class, 'add']);

    $routes->match(['get', 'post'], 'plans/edit/(:num)', [AdminPlans::class, 'edit/$1']);

    $routes->post('plans/show/(:num)', [AdminPlans::class, 'show/$1']);

    $routes->post('plans/edit', [AdminPlans::class, 'edit']);

    $routes->post('plans/switchToggle', [AdminPlans::class, 'switchToggle']);

    $routes->post('plans/delete', [AdminPlans::class, 'delete']);


});

/*** Route for Plans Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Plans\Controllers'],
    static function ($routes) {

    $routes->get('plans', [Plans::class, 'index']);
    $routes->post('plans/show/(:num)', [Plans::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('plans');
});*/
