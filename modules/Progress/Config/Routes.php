<?php

use Modules\Progress\Controllers\AdminProgress;
use Modules\Progress\Controllers\Progress;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Progress ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Progress\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    // Admin routes for managing progress tracking
    $routes->match(['GET', 'POST'], 'progress', [AdminProgress::class, 'index']);
    $routes->post('progress/index', [AdminProgress::class, 'index']);
    $routes->get('progress/edit/(:num)', [AdminProgress::class, 'edit/$1']);
    $routes->post('progress/edit/(:num)', [AdminProgress::class, 'edit/$1']);
    $routes->get('progress/show/(:num)', [AdminProgress::class, 'show/$1']);
    $routes->post('progress/delete', [AdminProgress::class, 'delete']);
    $routes->get('progress/dashboard', [AdminProgress::class, 'dashboard']);
    $routes->get('progress/user-analytics/(:num)', [AdminProgress::class, 'userAnalytics/$1']);
    $routes->get('progress/course-analytics/(:num)', [AdminProgress::class, 'courseAnalytics/$1']);
    $routes->match(['GET', 'POST'], 'progress/update', [AdminProgress::class, 'updateProgress']);
    $routes->post('progress/reset', [AdminProgress::class, 'resetProgress']);
    $routes->get('progress/export', [AdminProgress::class, 'exportProgress']);
});

/*** Additional Admin Routes ***/
$routes->group('admin', [
    'namespace' => 'Modules\Progress\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    $routes->get('progress/export', [AdminProgress::class, 'exportProgress']);
});

/**
 * Site Progress Module Routes
 */

// Progress tracking API endpoints
$routes->group('progress', ['namespace' => 'Modules\Progress\Controllers'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', [Progress::class, 'dashboard'], ['filter' => 'site_filter']);
    
    // AJAX API endpoints for progress tracking
    $routes->post('update', [Progress::class, 'updateProgress'], ['filter' => 'site_filter']);
    $routes->post('mark-completed', [Progress::class, 'markCompleted'], ['filter' => 'site_filter']);
    
    // Get progress data
    $routes->get('user/(:num)', [Progress::class, 'getUserProgress/$1'], ['filter' => 'site_filter']);
    $routes->get('course/(:num)', [Progress::class, 'getCourseProgress/$1'], ['filter' => 'site_filter']);
    $routes->get('unit/(:num)', [Progress::class, 'getUnitProgress/$1'], ['filter' => 'site_filter']);
    
    // Export progress data
    $routes->get('export', [Progress::class, 'exportProgress'], ['filter' => 'site_filter']);
    
    // Admin analytics endpoints
    $routes->get('analytics', [Progress::class, 'getAnalytics'], ['filter' => 'admin_filter']);
});

// Backward compatibility routes
$routes->group('api/progress', ['namespace' => 'Modules\Progress\Controllers'], function ($routes) {
    $routes->post('update', [Progress::class, 'updateProgress'], ['filter' => 'site_filter']);
    $routes->post('complete', [Progress::class, 'markCompleted'], ['filter' => 'site_filter']);
    $routes->get('user/(:num)', [Progress::class, 'getUserProgress/$1'], ['filter' => 'site_filter']);
});