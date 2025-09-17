<?php

use Modules\Admin\Controllers\Admin;

/**
 * Admin Module Routes
 */

// Admin dashboard and analytics routes
$routes->group('admin', ['namespace' => 'Modules\Admin\Controllers'], function ($routes) {
    // Main dashboard
    $routes->get('dashboard', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
    $routes->get('/', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
    
    // System health and monitoring
    $routes->get('system-health', [Admin::class, 'systemHealth'], ['filter' => 'admin_filter']);
    
    // Analytics export
    $routes->get('export-analytics', [Admin::class, 'exportAnalytics'], ['filter' => 'admin_filter']);
});

// Alternative admin routes for backward compatibility
$routes->get('dt_admin/analytics', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
$routes->get('dt_admin/system-health', [Admin::class, 'systemHealth'], ['filter' => 'admin_filter']);
$routes->get('dt_admin/export', [Admin::class, 'exportAnalytics'], ['filter' => 'admin_filter']);