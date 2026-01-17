<?php

use Modules\Enrollments\Controllers\AdminEnrollments;
use Modules\Enrollments\Controllers\Enrollments;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/**
 * Admin Enrollments Routes
 * - All routes here require 'admin_filter'
 * - Points to AdminEnrollments controller for listing, adding, editing, etc.
 */
$routes->group('dt_admin', [
    'namespace' => 'Modules\Enrollments\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {

    // List Enrollments (DataTables usage)
    $routes->match(['GET', 'POST'], 'enrollments', [AdminEnrollments::class, 'index']);
    $routes->post('enrollments/index', [AdminEnrollments::class, 'index']);
    // Add new enrollment (or payment record)
    $routes->match(['GET', 'POST'], 'enrollments/add', [AdminEnrollments::class, 'add']);
    // Edit enrollment
    $routes->match(['GET', 'POST'], 'enrollments/edit/(:num)', [AdminEnrollments::class, 'edit/$1']);
    // Show enrollment details
    $routes->post('enrollments/show/(:num)', [AdminEnrollments::class, 'show/$1']);
    // Switch toggle (active/inactive or status toggles)
    $routes->post('enrollments/switchToggle', [AdminEnrollments::class, 'switchToggle']);
    // Delete enrollment
    $routes->post('enrollments/delete', [AdminEnrollments::class, 'delete']);

    // Admin routes for course enrollments
    $routes->match(['GET', 'POST'], 'enrollments/courses', [AdminEnrollments::class, 'courseEnrollments']);
    $routes->get('enrollments/courses/show/(:num)', [AdminEnrollments::class, 'showCourseEnrollment/$1']);
    $routes->post('enrollments/courses/approve/(:num)', [AdminEnrollments::class, 'approveCourseEnrollment/$1']);
    $routes->post('enrollments/courses/reject/(:num)', [AdminEnrollments::class, 'rejectCourseEnrollment/$1']);
    $routes->get('enrollments/courses/stats', [AdminEnrollments::class, 'getCourseEnrollmentStats']);
    $routes->get('enrollments/courses/pending-count', [AdminEnrollments::class, 'getPendingCourseEnrollmentsCount']);
});


/**
 * Front-end Enrollments Routes
 * - Public routes for site visitors & enrolled users
 */
$routes->group('', [
    'namespace' => 'Modules\Enrollments\Controllers',
], static function ($routes) {

    // Example route to show a list of enrollments or payments
    $routes->get('enrollments', [Enrollments::class, 'index']);
    $routes->post('enrollments/show/(:num)', [Enrollments::class, 'show/$1']);

    // Course-based enrollment routes
    $routes->get('enrollments/courses-shop', [Enrollments::class, 'coursesShop']);
    $routes->get('enrollments/my-courses', [Enrollments::class, 'myCourses']);
    $routes->get('enrollments/purchase-course/(:num)', [Enrollments::class, 'purchaseCourse/$1']);
    $routes->get('enrollments/purchase-course', [Enrollments::class, 'purchaseCourse']);
    $routes->match(['GET', 'POST'], 'enrollments/course-checkout', [Enrollments::class, 'courseCheckout']);
});
