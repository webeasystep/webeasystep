<?php

use Modules\Users\Controllers\AdminUsers;
use Modules\Users\Controllers\InstructorPortal;
use Modules\Users\Controllers\Users;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Route for Users Admin ***/

$routes->group('dt_admin', ['namespace' => 'Modules\Users\Controllers',
    'filter' => 'admin_filter'], static function ($routes) {

    // All Users
    $routes->match(['GET', 'POST'], 'users',  [AdminUsers::class, 'index']);
    $routes->post('users/index', [AdminUsers::class, 'index']);
    $routes->match(['GET', 'POST'], 'users/add',  [AdminUsers::class, 'add']);
    $routes->match(['GET', 'POST'], 'users/edit/(:num)',  [AdminUsers::class, 'edit/$1']);
    $routes->post('users/show/(:num)', [AdminUsers::class, 'show/$1']);
    $routes->post('users/edit', [AdminUsers::class, 'edit']);
    $routes->post('users/switchToggle', [AdminUsers::class, 'switchToggle']);
    $routes->post('users/delete', [AdminUsers::class, 'delete']);

    // Students Routes
    $routes->match(['GET', 'POST'], 'students', [AdminUsers::class, 'students']);
    $routes->post('students/index', [AdminUsers::class, 'students']);
    $routes->match(['GET', 'POST'], 'students/add', [AdminUsers::class, 'add/1']);
    $routes->match(['GET', 'POST'], 'students/edit/(:num)', [AdminUsers::class, 'edit/$1/1']);
    $routes->post('students/show/(:num)', [AdminUsers::class, 'show/$1']);
    $routes->post('students/switchToggle', [AdminUsers::class, 'switchToggle']);
    $routes->post('students/delete', [AdminUsers::class, 'delete']);

    // Instructors Routes
    $routes->match(['GET', 'POST'], 'instructors', [AdminUsers::class, 'instructors']);
    $routes->post('instructors/index', [AdminUsers::class, 'instructors']);
    $routes->match(['GET', 'POST'], 'instructors/add', [AdminUsers::class, 'add/2']);
    $routes->match(['GET', 'POST'], 'instructors/edit/(:num)', [AdminUsers::class, 'edit/$1/2']);
    $routes->post('instructors/show/(:num)', [AdminUsers::class, 'show/$1']);
    $routes->post('instructors/switchToggle', [AdminUsers::class, 'switchToggle']);
    $routes->post('instructors/delete', [AdminUsers::class, 'delete']);

    // Device Monitoring & Control Routes
    $routes->get('users/devices', [AdminUsers::class, 'devices']);
    $routes->get('users/devices/reset/(:num)', [AdminUsers::class, 'resetDevices/$1']);
    $routes->get('users/devices/toggle_block/(:num)', [AdminUsers::class, 'toggleDeviceBlock/$1']);

});

/*** Route for Users Site ***/

// Main register route (short URL) - outside group for proper routing
$routes->get('register', '\Modules\Users\Controllers\Users::register');
$routes->post('register', '\Modules\Users\Controllers\Users::register');
$routes->get('instructor_register', '\Modules\Users\Controllers\Users::instructorRegister');
$routes->post('instructor_register', '\Modules\Users\Controllers\Users::instructorRegister');

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
    $routes->get('users/instructor-register', [Users::class, 'instructorRegister']);
    $routes->post('users/instructor-register', [Users::class, 'instructorRegister']);

    // Settings routes (require authentication)
    $routes->group('', ['filter' => 'site_filter'], static function ($routes) {
        $routes->get('settings', [\Modules\Users\Controllers\Settings::class, 'index']);
        $routes->post('settings/update-profile', [\Modules\Users\Controllers\Settings::class, 'updateProfile']);
        $routes->post('settings/change-password', [\Modules\Users\Controllers\Settings::class, 'changePassword']);
        $routes->post('settings/upload-avatar', [\Modules\Users\Controllers\Settings::class, 'uploadAvatar']);
        $routes->post('settings/delete-avatar', [\Modules\Users\Controllers\Settings::class, 'deleteAvatar']);
    });
});

$routes->group('instructor', [
    'namespace' => 'Modules\Users\Controllers',
    'filter' => 'instructor_filter',
], static function ($routes) {
    $routes->get('dashboard', [InstructorPortal::class, 'dashboard']);
    $routes->get('courses', [InstructorPortal::class, 'courses']);
    $routes->get('orders', [InstructorPortal::class, 'orders']);
    $routes->get('faq', [InstructorPortal::class, 'faq']);
});
