<?php

namespace Modules\Articles\Config;

use Modules\Articles\Controllers\AdminArticles;
use Modules\Articles\Controllers\Articles;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Articles Admin ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Articles\Controllers',
    'filter'    => 'admin_filter'
], static function ($routes) {
    $routes->match(['GET', 'POST'], 'articles', [AdminArticles::class, 'index']);
    $routes->post('articles/index', [AdminArticles::class, 'index']);
    $routes->match(['GET', 'POST'], 'articles/add', [AdminArticles::class, 'add']);
    $routes->match(['GET', 'POST'], 'articles/edit/(:num)', [AdminArticles::class, 'edit/$1']);
    $routes->post('articles/show/(:num)', [AdminArticles::class, 'show/$1']);
    $routes->post('articles/edit', [AdminArticles::class, 'edit']);
    $routes->post('articles/switchToggle', [AdminArticles::class, 'switchToggle']);
    $routes->post('articles/delete', [AdminArticles::class, 'delete']);
});

/*** Route for Articles / Blog Site ***/
$routes->group('', ['namespace' => 'Modules\Articles\Controllers'], static function ($routes) {
    $routes->get('blog', [Articles::class, 'index']);
    $routes->get('blog/(:any)', [Articles::class, 'article_show/$1']);
    $routes->get('articles', [Articles::class, 'index']);
    $routes->get('articles/article_show/(:any)', [Articles::class, 'article_show/$1']);
    $routes->get('articles/(:any)', [Articles::class, 'article_show/$1']);
});
