<?php


use Modules\Permissions\Controllers\AdminPermissions;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Users Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Permissions\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

  //  example of permissions $routes->get('users', [AdminUsers::class, 'index'], ['filter' => 'admin_filter']);

    $routes->match(['get', 'post'], 'permissions',  [AdminPermissions::class, 'index']);
    $routes->post('permissions/index', [AdminPermissions::class, 'index']);

    $routes->match(['get', 'post'], 'permissions/add',  [AdminPermissions::class, 'add']);

    $routes->match(['get', 'post'], 'permissions/edit/(:num)',  [AdminPermissions::class, 'edit/$1']);

    $routes->post('permissions/show/(:num)', [AdminPermissions::class, 'show/$1']);

    $routes->post('permissions/switchToggle', [AdminPermissions::class, 'switchToggle']);

    $routes->post('permissions/delete', [AdminPermissions::class, 'delete']);

});

/*** Route for Users Site ***/


/*** Route for Users api ***/
/*
$routes->permission('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('users');
});*/
