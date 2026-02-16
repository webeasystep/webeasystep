<?php

use Modules\Articles\Controllers\AdminArticles;
use Modules\Articles\Controllers\Articles;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Sections Admin ***/

$routes->group('dt_admin', [
    'namespace' => 'Modules\Articles\Controllers',
    'filter' => 'admin_filter' // Apply the filter to the entire group
], static function ($routes) {
    //  example of permissions $routes->get('sections', [AdminSections::class, 'index'], ['filter' => 'admin_filter']);
    $routes->match(['GET', 'POST'], 'articles', [AdminArticles::class, 'index']);

    $routes->post('articles/index', [AdminArticles::class, 'index']);

    $routes->match(['GET', 'POST'], 'articles/add', [AdminArticles::class, 'add']);

    $routes->match(['GET', 'POST'], 'articles/edit/(:num)', [AdminArticles::class, 'edit/$1']);

    $routes->post('articles/show/(:num)', [AdminArticles::class, 'show/$1']);

    $routes->post('articles/edit', [AdminArticles::class, 'edit']);

    $routes->post('articles/switchToggle', [AdminArticles::class, 'switchToggle']);

    $routes->post('articles/delete', [AdminArticles::class, 'delete']);


});

/*** Route for Articles Site ***/
$routes->group('/',
    ['namespace' => 'Modules\Articles\Controllers'],
    static function ($routes) {
    $routes->get('articles/article_show/(:any)', [Articles::class, 'article_show/$1']);
    $routes->get('articles', [Articles::class, 'index']);
    $routes->post('articles/show/(:num)', [Articles::class, 'show/$1']);

});


/*** Route for Sections api ***/
/*
$routes->group('api', ['namespace' => 'App\API\v1'], static function ($routes) {
    $routes->resource('articles');
});*/
