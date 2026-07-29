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

// Debug route for session testing
$routes->get('debug_session', function() {
    $session = session();
    $data = [
        'session_data' => $session->get(),
        'user_id' => $session->get('user_id'),
        'auth_user' => auth()->user(),
        'is_logged_in' => auth()->loggedIn()
    ];
    return json_encode($data, JSON_PRETTY_PRINT);
});

// 1) قبل shield routes:
// $routes->match(['GET', 'POST'], 'register', [Site::class, 'register']); // تم تعطيله لصالح Users controller
// $routes->match(['GET', 'POST'], 'login', [Site::class, 'login']); // تم تعطيله لصالح Users controller
$routes->get('activate-account', [Site::class, 'activateAccount']);
$routes->get('activation-sent', function() {
    return MainView('site_layout/activation_sent');
});

// Custom routes for Shield actions MUST be defined before shield routes and with higher priority
$routes->get('auth/a/show', [\App\Controllers\ActivationController::class, 'show'], ['as' => 'auth-action-show', 'priority' => 1]);
$routes->post('auth/a/verify', [\App\Controllers\ActivationController::class, 'verify'], ['as' => 'auth-action-verify', 'priority' => 1]);

// Shield routes - exclude login, register, show, verify, and magic-link to avoid conflicts
service('auth')->routes($routes, ['except' => ['login', 'register', 'auth/a/show', 'auth/a/verify', 'magic-link']]);

// Password Reset Routes (Site)
$routes->get('forgot-password', [\App\Controllers\PasswordResetController::class, 'forgotForm']);
$routes->post('forgot-password', [\App\Controllers\PasswordResetController::class, 'sendCode']);
$routes->get('reset-password', [\App\Controllers\PasswordResetController::class, 'resetForm']);
$routes->post('reset-password', [\App\Controllers\PasswordResetController::class, 'resetPassword']);


// Site main routes
$routes->group('site', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->match(['GET', 'POST'], 'logout', [Site::class, 'logout']);

    $routes->match(['GET', 'POST'], 'search', 'Site::search', ['filter' => 'site_filter']);
    // Add dynamic segments for order_id and driver_id in the take_order route
    $routes->get('take_order/(:num)/(:num)', 'Site::take_order/$1/$2', ['filter' => 'site_filter']);
    $routes->get('post-login-redirect', [Site::class, 'handlePostLoginRedirect'], ['filter' => 'site_filter']);

});

// Leads API route (for fake door MVP)
$routes->post('leads/save', [\App\Controllers\Leads::class, 'save']);

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


});


/**
 * --------------------------------------------------------------------
 * Include Modules Routes Files
 * --------------------------------------------------------------------
 */

foreach (glob(ROOTPATH . 'modules/*', GLOB_ONLYDIR) as $item_dir) {
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
