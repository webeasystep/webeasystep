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

    // Admin routes for unit enrollments
    $routes->match(['GET', 'POST'], 'enrollments/units', [AdminEnrollments::class, 'unitEnrollments']);
    $routes->get('enrollments/units/show/(:num)', [AdminEnrollments::class, 'showUnitEnrollment/$1']);
    $routes->post('enrollments/units/approve/(:num)', [AdminEnrollments::class, 'approveUnitEnrollment/$1']);
    $routes->post('enrollments/units/reject/(:num)', [AdminEnrollments::class, 'rejectUnitEnrollment/$1']);
    $routes->get('enrollments/units/stats', [AdminEnrollments::class, 'unitEnrollmentStats']);
    $routes->get('enrollments/units/pending-count', [AdminEnrollments::class, 'getPendingUnitEnrollmentsCount']);
});


/**
 * Front-end Enrollments Routes
 * - Public routes for site visitors & enrolled users
 */
$routes->group('', [
    'namespace' => 'Modules\Enrollments\Controllers',
], static function ($routes) {

    // Test route to verify controller access
    $routes->get('enrollments/test', function() {
        return json_encode(['status' => 'success', 'message' => 'Enrollments controller accessible']);
    });

    // Test route using controller method
    $routes->get('enrollments/controller-test', [Enrollments::class, 'test']);

    // Example route to show a list of enrollments or payments
    $routes->get('enrollments', [Enrollments::class, 'index']);
    $routes->post('enrollments/show/(:num)', [Enrollments::class, 'show/$1']);

    // Site routes for unit purchases - consolidated to use purchase-units only
    $routes->get('enrollments/units-shop', [Enrollments::class, 'unitsShop']);
    $routes->match(['GET', 'POST'], 'enrollments/purchase-units', [Enrollments::class, 'purchaseUnits']);
    $routes->match(['GET', 'POST'], 'enrollments/checkout', [Enrollments::class, 'checkout']);
    $routes->post('enrollments/complete-enrollment', [Enrollments::class, 'completeEnrollment']);
    $routes->get('enrollments/my-purchases', [Enrollments::class, 'myPurchases']);
});
