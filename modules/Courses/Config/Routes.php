<?php

use Modules\Courses\Controllers\AdminCourses;
use Modules\Courses\Controllers\Courses;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Courses ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Courses\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    // Example admin routes for courses
    $routes->match(['GET', 'POST'], 'courses', [AdminCourses::class, 'index']);
    $routes->post('courses/index', [AdminCourses::class, 'index']);
    $routes->match(['GET', 'POST'], 'courses/add', [AdminCourses::class, 'add']);
    $routes->match(['GET', 'POST'], 'courses/edit/(:num)', [AdminCourses::class, 'edit/$1']);
    $routes->post('courses/show/(:num)', [AdminCourses::class, 'show/$1']);
    $routes->post('courses/edit', [AdminCourses::class, 'edit']);
    $routes->post('courses/switchToggle', [AdminCourses::class, 'switchToggle']);
    $routes->post('courses/delete', [AdminCourses::class, 'delete']);
});

/*** Site Routes for Courses ***/
$routes->group('/', [
    'namespace' => 'Modules\Courses\Controllers'
], static function ($routes) {
    // Existing route to list all courses (index)
    $routes->get('courses', [Courses::class, 'index']);
    $routes->get('courses/course_details/(:any)', 'Courses::course_details/$1');
    $routes->get('courses/course_view/(:any)', 'Courses::course_view/$1');
    $routes->get('courses/my_courses', 'Courses::my_courses');
    $routes->post('courses/show/(:num)', [Courses::class, 'show/$1']);
});

/*** Example API Routes (commented out) ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('courses');
});
*/
