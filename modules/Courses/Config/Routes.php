<?php

use Modules\Courses\Controllers\AdminCourses;
use Modules\Courses\Controllers\Courses;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Courses\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'courses', [AdminCourses::class, 'index']);

    $routes->post('courses/index', [AdminCourses::class, 'index']);

    $routes->match(['get', 'post'], 'courses/add', [AdminCourses::class, 'add']);

    $routes->match(['get', 'post'], 'courses/edit/(:num)', [AdminCourses::class, 'edit/$1']);

    $routes->post('courses/show/(:num)', [AdminCourses::class, 'show/$1']);

    $routes->post('courses/edit', [AdminCourses::class, 'edit']);

    $routes->post('courses/switchToggle', [AdminCourses::class, 'switchToggle']);

    $routes->get('courses/delete', [AdminCourses::class, 'delete']);


});

/*** Route for Courses Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Courses\Controllers'],
    static function ($routes) {

    $routes->get('courses', [Courses::class, 'index']);
    $routes->post('courses/show/(:num)', [Courses::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('courses');
});*/
