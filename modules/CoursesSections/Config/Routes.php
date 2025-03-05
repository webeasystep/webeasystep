<?php

use Modules\CoursesSections\Controllers\AdminCoursesSections;
use Modules\CoursesSections\Controllers\CoursesSections;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\CoursesSections\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'courses_sections', [AdminCoursesSections::class, 'index']);

    $routes->post('courses_sections/index', [AdminCoursesSections::class, 'index']);

    $routes->match(['GET', 'POST'], 'courses_sections/add', [AdminCoursesSections::class, 'add']);

    $routes->match(['GET', 'POST'], 'courses_sections/edit/(:num)', [AdminCoursesSections::class, 'edit/$1']);

    $routes->post('courses_sections/show/(:num)', [AdminCoursesSections::class, 'show/$1']);

    $routes->post('courses_sections/edit', [AdminCoursesSections::class, 'edit']);

    $routes->post('courses_sections/switchToggle', [AdminCoursesSections::class, 'switchToggle']);

    $routes->post('courses_sections/delete', [AdminCoursesSections::class, 'delete']);


});

/*** Route for CoursesSections Site ***/
$routes->group('/',
    ['namespace' => 'Modules\CoursesSections\Controllers'],
    static function ($routes) {

    $routes->get('courses_sections', [CoursesSections::class, 'index']);
    $routes->post('courses_sections/show/(:num)', [CoursesSections::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('courses_sections');
});*/
