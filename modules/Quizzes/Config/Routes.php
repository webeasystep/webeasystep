<?php

use Modules\Quizzes\Controllers\AdminQuizzes;
use Modules\Quizzes\Controllers\Quizzes;

if (!isset($routes)) {
    $routes = \Config\Services::routes(true);
}

/*** Admin Routes for Quizzes ***/
$routes->group('dt_admin', [
    'namespace' => 'Modules\Quizzes\Controllers',
    'filter' => 'admin_filter'
], static function ($routes) {
    // Admin routes for managing quizzes
    $routes->match(['GET', 'POST'], 'quizzes', [AdminQuizzes::class, 'index']);
    $routes->get('quizzes/create', [AdminQuizzes::class, 'create']);
    $routes->post('quizzes/store', [AdminQuizzes::class, 'create']);
    $routes->get('quizzes/store', function() {
        return redirect()->to('/dt_admin/quizzes/create');
    });
    $routes->match(['GET', 'POST'], 'quizzes/edit/(:num)', [AdminQuizzes::class, 'edit/$1']);
    $routes->post('quizzes/update/(:num)', [AdminQuizzes::class, 'edit/$1']);
    $routes->get('quizzes/view/(:num)', [AdminQuizzes::class, 'view/$1']);
    $routes->get('quizzes/questions/(:num)', [AdminQuizzes::class, 'questions/$1']);
    $routes->get('quizzes/import', [AdminQuizzes::class, 'importQuiz']);
    $routes->post('quizzes/import', [AdminQuizzes::class, 'importQuiz']);
    $routes->get('quizzes/attempts/(:num)', [AdminQuizzes::class, 'attempts/$1']);
    $routes->get('quizzes/attempts', [AdminQuizzes::class, 'attempts']);
    $routes->get('quizzes/view-attempt/(:num)', [AdminQuizzes::class, 'viewAttempt/$1']);
    $routes->post('quizzes/delete/(:num)', [AdminQuizzes::class, 'delete/$1']);
    $routes->get('quizzes/export/(:num)', [AdminQuizzes::class, 'exportQuiz/$1']);
    $routes->get('quizzes/analytics', [AdminQuizzes::class, 'analytics']);
    $routes->get('quizzes/statistics', [AdminQuizzes::class, 'getStatistics']);
    $routes->post('quizzes/toggle-status/(:num)', [AdminQuizzes::class, 'toggleStatus/$1']);
});

/*** Site Routes for Quizzes ***/
$routes->group('/', [
    'namespace' => 'Modules\Quizzes\Controllers'
], static function ($routes) {
    // Public quiz routes
    $routes->get('quizzes', [Quizzes::class, 'index']);
    $routes->get('quizzes/course/(:num)', [Quizzes::class, 'courseQuizzes/$1']);
    $routes->get('quizzes/view/(:num)', [Quizzes::class, 'viewQuiz/$1']);
    
    // Routes requiring authentication
    $routes->group('', ['filter' => 'site_filter'], static function ($routes) {
        // Quiz taking
        $routes->get('quizzes/take/(:num)', [Quizzes::class, 'takeQuiz/$1']);
        $routes->post('quizzes/submit/(:num)', [Quizzes::class, 'submitQuiz/$1']);
        $routes->get('quizzes/results/(:num)', [Quizzes::class, 'viewResults/$1']);
        $routes->get('quizzes/retry/(:num)', [Quizzes::class, 'retryQuiz/$1']);
        
        // User quiz history
        $routes->get('quizzes/my-attempts', [Quizzes::class, 'myAttempts']);
        $routes->get('quizzes/attempt/(:num)', [Quizzes::class, 'viewAttempt/$1']);
        
        // AJAX endpoints
        $routes->post('quizzes/ajax/save-progress', [Quizzes::class, 'saveProgress']);
        $routes->get('quizzes/ajax/time-remaining/(:num)', [Quizzes::class, 'getTimeRemaining/$1']);
    });
});

/*** API Routes for Quizzes ***/
$routes->group('api/quizzes', [
    'namespace' => 'Modules\Quizzes\Controllers',
    'filter' => 'site_filter'
], static function ($routes) {
    $routes->get('course/(:num)', [Quizzes::class, 'apiCourseQuizzes/$1']);
    $routes->get('(:num)', [Quizzes::class, 'apiQuizDetails/$1']);
    $routes->post('(:num)/submit', [Quizzes::class, 'apiSubmitQuiz/$1']);
    $routes->get('attempts/(:num)', [Quizzes::class, 'apiAttemptDetails/$1']);
});