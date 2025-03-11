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
    // Admin routes for managing courses
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

    // 1) List all courses (index)
    $routes->get('courses', [Courses::class, 'index']);
    // 2) Course details by slug
    $routes->get('courses/course_details/(:any)', 'Courses::course_details/$1');
    // 3) Course "player" view
    $routes->get('courses/course_view/(:any)', 'Courses::course_view/$1');
    // 4) My Courses (enrolled)
    $routes->get('courses/my_courses', 'Courses::my_courses');
    // 5) Show single course by ID (if you still use it)
    $routes->post('courses/show/(:num)', [Courses::class, 'show/$1']);
    // === NEW Routes ===

    // Enroll user in a course (POST to /courses/enroll/123)
    $routes->post('courses/enroll/(:num)', 'Courses::enroll/$1');
    // Mark a lesson as complete (POST to /courses/markLessonComplete)
    $routes->post('courses/markLessonComplete', 'Courses::markLessonComplete');
});

/*** Example API Routes (commented out) ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('courses');
});
*/
