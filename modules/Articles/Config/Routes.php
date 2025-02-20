<?php

use Modules\Articles\Controllers\AdminCourses;
use Modules\Articles\Controllers\Courses;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Articles\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['get', 'post'], 'articles', [AdminCourses::class, 'index']);

    $routes->post('articles/index', [AdminCourses::class, 'index']);

    $routes->match(['get', 'post'], 'articles/add', [AdminCourses::class, 'add']);

    $routes->match(['get', 'post'], 'articles/edit/(:num)', [AdminCourses::class, 'edit/$1']);

    $routes->post('articles/show/(:num)', [AdminCourses::class, 'show/$1']);

    $routes->post('articles/edit', [AdminCourses::class, 'edit']);

    $routes->post('articles/switchToggle', [AdminCourses::class, 'switchToggle']);

    $routes->get('articles/delete', [AdminCourses::class, 'delete']);


});

/*** Route for Articles Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Articles\Controllers'],
    static function ($routes) {

    $routes->get('articles', [Courses::class, 'index']);
    $routes->post('articles/show/(:num)', [Courses::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('articles');
});*/
