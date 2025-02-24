<?php

use Modules\ContactUs\Controllers\AdminContactUs;
use Modules\ContactUs\Controllers\ContactUs;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for ContactUs Admin ***/

    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);

$routes->group('dt_admin', ['namespace' => 'Modules\ContactUs\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'contact_us',  [AdminContactUs::class, 'index']);

    $routes->post('contact_us/index', [AdminContactUs::class, 'index']);

    $routes->match(['get', 'post'], 'contact_us/add',  [AdminContactUs::class, 'add']);

    $routes->match(['get', 'post'], 'contact_us/edit/(:num)',  [AdminContactUs::class, 'edit/$1']);

    $routes->post('contact_us/show/(:num)', [AdminContactUs::class, 'show/$1']);

    $routes->post('contact_us/edit', [AdminContactUs::class, 'edit']);

    $routes->post('contact_us/switchToggle', [AdminContactUs::class, 'switchToggle']);

    $routes->post('contact_us/delete', [AdminContactUs::class, 'delete']);



});

/*** Route for ContactUs Site ***/

$routes->group('/', ['namespace' => 'Modules\ContactUs\Controllers'], static function ($routes) {
    $routes->match(['get', 'post'], 'contact_us',  [ContactUs::class, 'index']);
    $routes->match(['get', 'post'], 'contact_us',  [ContactUs::class, 'index']);
    $routes->match(['get', 'post'], 'contact_us/subscribe',  [ContactUs::class, 'subscribe']);

});

/*** Route for ContactUs api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('contact_us');
});*/
