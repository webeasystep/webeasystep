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
    $routes->get('courses/get-all', [AdminCourses::class, 'getAllCourses']);

    // Quiz management routes
    $routes->get('courses/getQuizzesByCourse/(:num)', [AdminCourses::class, 'getQuizzesByCourse/$1']);
    $routes->post('courses/addQuizToCourse', [AdminCourses::class, 'addQuizToCourse']);
    $routes->get('courses/show/(:num)', [AdminCourses::class, 'show/$1']);

});

// Public Site Routes (no authentication required)
$routes->group('', [
    'namespace' => 'Modules\Courses\Controllers'
], static function ($routes) {
    $routes->get('courses', 'Courses::index');
    $routes->get('courses/fundamentals', 'Courses::fundamentals');
    $routes->get('courses/course_details/(:any)', 'Courses::course_details/$1');
});

// Protected Site Routes (authentication required)
$routes->group('', [
    'namespace' => 'Modules\Courses\Controllers',
    'filter' => 'site_filter'
], static function ($routes) {
    $routes->get('courses/course_view/(:any)', 'Courses::course_view/$1');
    $routes->get('courses/item/(:num)', 'Courses::item/$1');
    $routes->get('courses/item/(:num)', 'Courses::item/$1');
    // $routes->get('courses/my_courses', 'Courses::my_courses'); // Deprecated - use enrollments/my-courses
    $routes->post('courses/enroll/(:num)', 'Courses::enroll/$1');
    $routes->post('courses/enroll/(:num)', 'Courses::enroll/$1');
    $routes->post('courses/markLessonComplete', 'Courses::markLessonComplete');
});

/*** Example API Routes (commented out) ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('courses');
});
*/
