<?php

use Modules\Settings\Controllers\AdminSettings;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Users Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Settings\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

    //  example of Settings $routes->get('users', [AdminUsers::class, 'index'], ['filter' => 'admin_filter']);

    $routes->match(['GET', 'POST'], 'settings',  [AdminSettings::class, 'index']);

    $routes->post('settings/index', [AdminSettings::class, 'index']);

    $routes->match(['GET', 'POST'], 'settings/add',  [AdminSettings::class, 'add']);

    $routes->match(['GET', 'POST'], 'settings/edit/(:num)',  [AdminSettings::class, 'edit/$1']);

    $routes->post('settings/show/(:num)', [AdminSettings::class, 'show/$1']);

    $routes->post('settings/edit', [AdminSettings::class, 'edit']);

    $routes->post('settings/switchToggle', [AdminSettings::class, 'switchToggle']);

    $routes->match(['GET', 'POST'], 'settings/general_panel',  [AdminSettings::class, 'general_panel']);
    $routes->match(['GET', 'POST'], 'settings/social_links_panel',  [AdminSettings::class, 'social_links_panel']);
    $routes->match(['GET', 'POST'], 'settings/change_password_panel',  [AdminSettings::class, 'change_password_panel']);
    $routes->match(['GET', 'POST'], 'settings/notifications_panel',  [AdminSettings::class, 'notifications_panel']);

});

/*** Route for Users Site ***/


/*** Route for Users api ***/
/*
$routes->permission('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('users');
});*/
