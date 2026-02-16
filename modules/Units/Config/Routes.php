<?php

use Modules\Units\Controllers\AdminUnits;
use Modules\Units\Controllers\Units;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Units ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Units\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    // Admin routes for managing units
    $routes->match(['GET', 'POST'], 'units', 'AdminUnits::index');
    $routes->match(['GET', 'POST'], 'units/add', 'AdminUnits::add');
    $routes->match(['GET', 'POST'], 'units/create', 'AdminUnits::add');
    $routes->post('units/create', 'AdminUnits::add');
    $routes->match(['GET', 'POST'], 'units/edit/(:num)', 'AdminUnits::edit/$1');
    $routes->get('units/show/(:num)', 'AdminUnits::show/$1');
    $routes->post('units/deleteUnit/(:num)', 'AdminUnits::deleteUnit/$1');
    $routes->post('units/delete', 'AdminUnits::delete');
    $routes->get('units/get-units-by-course/(:num)', 'AdminUnits::getUnitsByCourse/$1');
    $routes->get('units/get-quizzes-by-course/(:num)', 'AdminUnits::getQuizzesByCourse/$1');
    $routes->get('units/get-available-pages', 'AdminUnits::getAvailablePages');
$routes->post('units/fetch-video-data', 'AdminUnits::fetchVideoData');
$routes->get('units/get-available-quizzes/(:num)', 'AdminUnits::getAvailableQuizzes/$1');
    $routes->get('units/statistics', 'AdminUnits::statistics');
    $routes->get('units/statistics/(:num)', 'AdminUnits::unitStatistics/$1');
    $routes->post('units/duplicate/(:num)', 'AdminUnits::duplicate/$1');
    $routes->post('units/remove-quiz', 'AdminUnits::removeQuiz');
    $routes->post('units/toggle-status/(:num)', 'AdminUnits::toggleStatus/$1');
    $routes->post('units/switchToggle', [AdminUnits::class, 'switchToggle']);

    // Payment management routes
    $routes->match(['GET', 'POST'], 'units/payments', 'AdminUnits::payments');
    $routes->get('units/payment/(:num)', 'AdminUnits::viewPayment/$1');
    $routes->post('units/approve-payment/(:num)', 'AdminUnits::approvePayment/$1');
    $routes->post('units/reject-payment/(:num)', 'AdminUnits::rejectPayment/$1');

    // Purchase management routes
    $routes->match(['GET', 'POST'], 'units/purchases', 'AdminUnits::purchases');
});

/*** Site Routes for Units ***/
$routes->group('/', [
    'namespace' => 'Modules\Units\Controllers'
], static function ($routes) {
    // Public unit routes
    $routes->get('units/shop', [Units::class, 'shop']);
    $routes->get('units/view/(:num)', [Units::class, 'viewUnit/$1']);

    // Routes with authentication filter
    $routes->group('', ['filter' => 'site_filter'], static function ($routes) {
        // Unit purchase routes
        $routes->match(['GET', 'POST'], 'units/submit-payment', [Units::class, 'submitPayment']);
        $routes->get('units/my-purchases', [Units::class, 'myPurchases']);

        // Unit progress routes
        $routes->post('units/mark-complete', [Units::class, 'markComplete']);
        $routes->post('units/update-progress', [Units::class, 'updateProgress']);
        $routes->get('units/get-progress', [Units::class, 'getProgress']);
    });
});
