<?php


use Modules\Groups\Controllers\AdminGroups;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Users Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Groups\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

  //  example of permissions $routes->get('users', [AdminUsers::class, 'index'], ['filter' => 'admin_filter']);

    $routes->match(['get', 'post'], 'groups',  [AdminGroups::class, 'index']);
    $routes->post('groups/index', [AdminGroups::class, 'index']);

    $routes->match(['get', 'post'], 'groups/add',  [AdminGroups::class, 'add']);

    $routes->match(['get', 'post'], 'groups/edit/(:num)',  [AdminGroups::class, 'edit/$1']);

    $routes->post('groups/show/(:num)', [AdminGroups::class, 'show/$1']);

    $routes->post('groups/edit', [AdminGroups::class, 'edit']);

    $routes->post('groups/switchToggle', [AdminGroups::class, 'switchToggle']);

    $routes->post('groups/delete', [AdminGroups::class, 'delete']);

});

/*** Route for Users Site ***/


/*** Route for Users api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('users');
});*/
