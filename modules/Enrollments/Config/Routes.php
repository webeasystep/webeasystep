<?php

use Modules\Enrollments\Controllers\AdminEnrollments;
use Modules\Enrollments\Controllers\Enrollments;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Enrollments\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'enrollments', [AdminEnrollments::class, 'index']);

    $routes->post('enrollments/index', [AdminEnrollments::class, 'index']);

    $routes->match(['GET', 'POST'], 'enrollments/add', [AdminEnrollments::class, 'add']);

    $routes->match(['GET', 'POST'], 'enrollments/edit/(:num)', [AdminEnrollments::class, 'edit/$1']);

    $routes->post('enrollments/show/(:num)', [AdminEnrollments::class, 'show/$1']);

    $routes->post('enrollments/edit', [AdminEnrollments::class, 'edit']);

    $routes->post('enrollments/switchToggle', [AdminEnrollments::class, 'switchToggle']);

    $routes->post('enrollments/delete', [AdminEnrollments::class, 'delete']);


});

/*** Route for Enrollments Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Enrollments\Controllers'],
    static function ($routes) {

    $routes->get('enrollments', [Enrollments::class, 'index']);
    $routes->post('enrollments/show/(:num)', [Enrollments::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('enrollments');
});*/
