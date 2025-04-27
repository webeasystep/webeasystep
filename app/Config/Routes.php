<?php

namespace Config;

// Create a new instance of our RouteCollection class.
use CodeIgniter\Router\RouteCollection;
use App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Controllers\Site;

$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->setAutoRoute(false);
// Define Pages routes
/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */


/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Site::class, 'index']);

$routes->get('lang/{locale}', [BaseController::class, 'langSwitch']);

// 1) قبل shield routes:
$routes->match(['get', 'post'], 'register', [Site::class, 'register']);
$routes->match(['get', 'post'], 'login', [Site::class, 'login']);

service('auth')->routes($routes);


// Site main routes
$routes->group('site', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->match(['GET', 'POST'], 'logout', [Site::class, 'logout']);
    $routes->match(['GET', 'POST'], 'forget_password', [Site::class, 'forget_password']);
    $routes->match(['GET', 'POST'], 'reset_password', [Site::class, 'reset_password']);

    $routes->match(['GET', 'POST'], 'search', 'Site::search', ['filter' => 'site_filter']);
    // Add dynamic segments for order_id and driver_id in the take_order route
    $routes->get('take_order/(:num)/(:num)', 'Site::take_order/$1/$2', ['filter' => 'site_filter']);

});


// admin main routes 'filter' => 'admin_filter',

$routes->group('dt_admin', ['namespace' => 'App\Controllers'], static function ($routes) {
   // $routes->get('/', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
    $routes->get('/', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
    $routes->get('dashboard', [Admin::class, 'dashboard'], ['filter' => 'admin_filter']);
    $routes->get('logout', [Admin::class, 'logout'], ['filter' => 'admin_filter']);

    $routes->match(['GET', 'POST'], 'login', [Admin::class, 'login']);
    $routes->match(['GET', 'POST'], 'register', [Admin::class, 'register']);
    $routes->match(['GET', 'POST'], 'reset_password', [Admin::class, 'reset_password']);
    $routes->match(['GET', 'POST'], 'forget_password', [Admin::class, 'forget_password']);
    $routes->match(['GET', 'POST'], 'verify_magic_link', [Admin::class, 'verify_magic_link']);

});


/**
 * --------------------------------------------------------------------
 * Include Modules Routes Files
 * --------------------------------------------------------------------
 */

foreach (glob(ROOTPATH . 'Modules/*', GLOB_ONLYDIR) as $item_dir) {
    if (file_exists($item_dir . '/Config/Routes.php')) {
        require_once($item_dir . '/Config/Routes.php');
    }
}

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */


if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}


