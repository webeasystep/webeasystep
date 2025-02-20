<?php
use Modules\Subscriptions\Controllers\AdminSubscriptions;
use Modules\Subscriptions\Controllers\Subscriptions;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Subscriptions\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'subscriptions', [AdminSubscriptions::class, 'index']);

    $routes->post('subscriptions/index', [AdminSubscriptions::class, 'index']);

    $routes->match(['get', 'post'], 'subscriptions/add', [AdminSubscriptions::class, 'add']);

    $routes->match(['get', 'post'], 'subscriptions/edit/(:num)', [AdminSubscriptions::class, 'edit/$1']);

    $routes->post('subscriptions/show/(:num)', [AdminSubscriptions::class, 'show/$1']);

    $routes->post('subscriptions/edit', [AdminSubscriptions::class, 'edit']);

    $routes->post('subscriptions/switchToggle', [AdminSubscriptions::class, 'switchToggle']);

    $routes->get('subscriptions/delete', [AdminSubscriptions::class, 'delete']);


});

/*** Route for Plans Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Subscriptions\Controllers'],
    static function ($routes) {

    $routes->get('subscriptions', [Subscriptions::class, 'index']);
    $routes->post('subscriptions/show/(:num)', [Subscriptions::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('subscriptions');
});*/
