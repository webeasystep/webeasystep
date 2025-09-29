<?php


use Modules\Users\Controllers\AdminUsers;
use Modules\Users\Controllers\Users;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Users Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Users\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

  //  example of permissions $routes->get('users', [AdminUsers::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'users',  [AdminUsers::class, 'index']);
    $routes->post('users/index', [AdminUsers::class, 'index']);

    $routes->match(['GET', 'POST'], 'users/add',  [AdminUsers::class, 'add']);

    $routes->match(['GET', 'POST'], 'users/edit/(:num)',  [AdminUsers::class, 'edit/$1']);

    $routes->post('users/show/(:num)', [AdminUsers::class, 'show/$1']);

    $routes->post('users/edit', [AdminUsers::class, 'edit']);

    $routes->post('users/switchToggle', [AdminUsers::class, 'switchToggle']);

    $routes->post('users/delete', [AdminUsers::class, 'delete']);

});

/*** Route for Users Site ***/

$routes->group('/', ['namespace' => 'Modules\Users\Controllers'], static function ($routes) {
    $routes->get('users', [Users::class, 'index']);
    $routes->post('users/show/(:num)', [Users::class, 'show/$1']);
    $routes->get('users/add', [Users::class, 'add']);
    $routes->get('users/edit/(:num)', [Users::class, 'edit/$1']);
    $routes->get('users/delete/(:num)', [Users::class, 'delete/$1']);

    // Authentication routes - Main login route
    $routes->match(['GET', 'POST'], 'login', [Users::class, 'login']);
    $routes->post('login', [Users::class, 'processLogin']);
    
    // Other authentication routes
    $routes->get('users/login', [Users::class, 'login']);
    $routes->post('users/login', [Users::class, 'processLogin']);
    $routes->get('users/logout', [Users::class, 'logout']);
    $routes->get('users/register', [Users::class, 'register']);
    $routes->post('users/register', [Users::class, 'register']);
    $routes->get('users/verify-email-sent', [Users::class, 'verifyEmailSent']);
    $routes->get('users/verify-email/(:any)', [Users::class, 'verifyEmail/$1']);

    // Settings routes (require authentication)
    $routes->group('', ['filter' => 'site_filter'], static function ($routes) {
        $routes->get('settings', [\Modules\Users\Controllers\Settings::class, 'index']);
        $routes->post('settings/update-profile', [\Modules\Users\Controllers\Settings::class, 'updateProfile']);
        $routes->post('settings/change-password', [\Modules\Users\Controllers\Settings::class, 'changePassword']);
        $routes->post('settings/upload-avatar', [\Modules\Users\Controllers\Settings::class, 'uploadAvatar']);
        $routes->post('settings/delete-avatar', [\Modules\Users\Controllers\Settings::class, 'deleteAvatar']);
    });
});

/*** Route for Users api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('users');
});*/
