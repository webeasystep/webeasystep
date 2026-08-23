<?php

use Modules\Cart\Controllers\Cart;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

$routes->group('', [
    'namespace' => 'Modules\Cart\Controllers',
], static function ($routes) {
    $routes->get('cart', [Cart::class, 'viewCart']);
    $routes->post('cart/add', [Cart::class, 'addItem']);
    $routes->post('cart/remove', [Cart::class, 'removeItem']);
    $routes->get('cart/count', [Cart::class, 'getCount']);
    $routes->get('cart/checkout/success', [Cart::class, 'checkoutSuccess']);
    $routes->match(['GET', 'POST'], 'cart/checkout', [Cart::class, 'checkout']);
});
