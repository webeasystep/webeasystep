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
});


/**
 * Front-end Enrollments Routes
 * - Public routes for site visitors & enrolled users
 */
$routes->group('/', [
    'namespace' => 'Modules\Enrollments\Controllers',
], static function ($routes) {

    // Example route to show a list of enrollments or payments
    $routes->get('enrollments', [Enrollments::class, 'index']);
    $routes->post('enrollments/show/(:num)', [Enrollments::class, 'show/$1']);

    // Main route for user checkout / enrolling in a course
    // e.g. GET/POST => /checkout/123
    $routes->match(['GET', 'POST'], 'checkout/(:num)', [Enrollments::class, 'checkout/$1']);
});
