<?php
namespace Modules\CourseRequests\Config;

$routes->group('api', ['namespace' => 'Modules\CourseRequests\Controllers'], static function ($routes) {
    $routes->post('course-requests', 'CourseRequestsController::submitRequest');
    $routes->get('departments/(:num)', 'CourseRequestsController::getDepartmentsByCollege/$1');
});
