<?php

namespace Modules\CourseRequests\Config;

use Modules\CourseRequests\Controllers\AdminCourseRequests;
use Modules\CourseRequests\Controllers\CourseRequestsController;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Course Requests ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\CourseRequests\Controllers',
    'filter'    => 'admin_filter'
], static function ($routes) {
    $routes->match(['GET', 'POST'], 'course_requests', [AdminCourseRequests::class, 'index']);
    $routes->post('course_requests/index', [AdminCourseRequests::class, 'index']);
    $routes->post('course_requests/show/(:num)', [AdminCourseRequests::class, 'show/$1']);
    $routes->post('course_requests/delete', [AdminCourseRequests::class, 'delete']);
    $routes->post('course_requests/update_status', [AdminCourseRequests::class, 'updateStatus']);
});

/*** Public / API Routes ***/
$routes->group('api', [
    'namespace' => 'Modules\CourseRequests\Controllers'
], static function ($routes) {
    $routes->post('course-requests', 'CourseRequestsController::submitRequest');
    $routes->get('departments/(:num)', 'CourseRequestsController::getDepartmentsByCollege/$1');
});
